<?php

namespace App\Http\Controllers\Admin;

use DB;
use DOMDocument;
use Carbon\Carbon;
use App\Models\Brand;
use League\Csv\Reader;
use App\Helpers\Helper;
use App\Models\Gallery;
use App\Models\License;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Attribute;
use League\Csv\Statement;
use App\Classes\XMLHelper;
use App\Enums\AssociationType;
use App\Models\Gallery360;
use App\Events\BackInStock;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Facades\MercadoLivre;
use App\Models\Childcategory;
use App\Models\Generalsetting;
use App\Models\AttributeOption;
use Yajra\DataTables\DataTables;
use App\Models\ProductTranslation;
use App\Models\CategoryTranslation;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\SubcategoryTranslation;
use Illuminate\Support\Facades\Session;
use App\Models\ChildcategoryTranslation;
use Illuminate\Support\Facades\DB as FacadeDB;
use function League\Csv\delimiter_detect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Artisan;

class ProductController extends Controller
{
    protected $store_id;
    protected $brand_id;
    protected $category_id;
    private $xml_helper;

    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->xml_helper = new XMLHelper();

        parent::__construct();
    }

    public function updateAttributesStatus(Request $request)
    {
        try {
            // Executa o comando Artisan para atualizar o status dos produtos
            Artisan::call('products:update-status');

            // Retorna uma mensagem de sucesso
            return redirect()->back()->with('success', 'Status dos produtos atualizado com sucesso!');
        } catch (\Exception $e) {
            // Retorna uma mensagem de erro
            return redirect()->back()->with('error', 'Erro ao atualizar o status: ' . $e->getMessage());
        }
    }

    public function updateXMLComprasParaguai()
    {
        try {
            $this->xml_helper->updateComprasparaguai();

            $msg = __("Compras Paraguai XML successfully updated.");
            return response()->json($msg);
        } catch (\Exception $e) {
            Log::error('error_update_loja_compras_xml', [$e->getMessage()]);
        }
    }

    public function updateXMLGoogleAndFacebook()
    {
        try {
            $this->xml_helper->updateLojaGoogle();
            $this->xml_helper->updateLojaFacebook();

            $msg = __('Google and Facebook XMLs successfully updated.');
            return response()->json($msg);
        } catch (\Exception $e) {
            Log::error('error_update_loja_facebook_and_google_xml', [$e->getMessage()]);
        }
    }

    //*** JSON Request
    public function datatables($status = null)
    {
        $datas = Product::where('user_id', 0);

        switch ($status) {
            case 'active':
                $datas->where('status', 1);
                break;
            case 'inactive':
                $datas->where('status', 0);
                break;
            case 'without_image':
                $datas->whereNull('photo')->orWhere('photo', '');
                break;
            case 'without_details':
                $datas->whereTranslation('details', null, $this->lang->locale);
                break;
            case 'featured':
                $datas->where('featured', 1);
                break;
            case 'latest':
                $datas->where('latest', 1);
                break;
            case 'without_category':
                $datas->whereNull('category_id')->orWhere('category_id', 0);
                break;
            case 'active_without_image':
                $datas->whereRaw('(photo IS NULL OR photo = "")')->where('status', 1);
                break;
            case 'with_image':
                $datas->whereNotNull('photo')->where('photo', '<>', '');
                break;
            case 'Activate_products_with_image':
                $datas->where('status', 1)->whereNotNull('photo')->where('photo', '<>', '');
                break;
            case 'Inative_products_with_image':
                $datas->where('status', 0)->whereNotNull('photo')->where('photo', '<>', '');
                break;
            case 'Out-of-stock_and_active_products':
                $datas->where('stock', 0)->where('status', 1);
                break;
            case 'Stock_and_inactive_products':
                $datas->where('stock', '>=', 1)->where('status', 0);
                break;
            case 'system_name':
                $query1 = DB::table('products')
                    ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
                    ->whereRaw('products.external_name = product_translations.name')
                    ->where('product_translations.locale', $this->lang->locale)
                    ->select('products.id');
                $datas->whereIn('id', $query1);
                break;
            case 'catalog':
                $datas->where('is_catalog', 1);
                break;
            case 'with_tags':
                $query = DB::table('products')
                    ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
                    ->whereNotNull('product_translations.features')
                    ->where('product_translations.features', '<>', '')
                    ->where('product_translations.locale', $this->lang->locale)
                    ->select('products.id');
                $datas->whereIn('id', $query);
                break;
            case 'without_tags':
                $datas->whereTranslation('features', null, $this->lang->locale);
                break;
        }
        $datas->orderBy('id', 'desc');        
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('brand', function (Product $data) {
                return $data->brand->name;
            })
            ->editColumn('category', function (Product $data) {
                return $data->category->name;
            })
            ->editColumn('store', function (Product $data) {
                foreach ($data->stores as $store) {
                    return $store->domain;
                }
            })
            ->addColumn('action', function (Product $data) {
                $meliRoute = config('mercadolivre.is_active') ? (
                    $data->mercadolivre_id 
                        ? route('admin-prod-meli-update', $data->id) 
                        : route('admin-prod-meli-send', $data->id)
                ) : null;
            
                $meliText = $data->mercadolivre_id 
                    ? __("Update at Mercado Livre") 
                    : __("Send to Mercado Livre");
            
                // Constrói o HTML das ações
                $actions = [
                    'edit' => '<a href="' . route('admin-prod-edit', $data->id) . '"><i class="fas fa-edit"></i> ' . __('Edit') . '</a>',
                    'copy' => '<a href="javascript:;" data-href="' . route('admin-prod-copy', $data->id) . '" data-toggle="modal" data-target="#confirm-copy" class="delete"><i class="fas fa-copy"></i> ' . __('Copy') . '</a>',
                    'meli' => $meliRoute ? '<a href="' . $meliRoute . '"><i class="fas fa-upload"></i> ' . $meliText . '</a>' : '',
                    'gallery' => '<a href="javascript" data-header="' . __("Image Gallery") . '" class="set-gallery-product" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="' . $data->id . '"><i class="fas fa-eye"></i> ' . __('View Gallery') . '</a>',
                    'delete' => '<a href="javascript:;" data-href="' . route('admin-prod-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>',
                ];
            
                // Concatena as ações e retorna
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">' . implode('', array_filter($actions)) . '</div>
                </div>';
            })            
            ->filterColumn('brand_id', function ($query, $keyword) {
                $this->brand_id = $keyword;
                $query->where('brand_id', $this->brand_id);
            })
            ->filterColumn('category_id', function ($query, $keyword) {
                $this->category_id = $keyword;
                $query->where('category_id', $this->category_id);
            })
            ->filterColumn('store_id', function ($query, $keyword) {
                $this->store_id = $keyword;
                $query->whereHas('stores', function ($query) {
                    $query->where('store_id', $this->store_id);
                });
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->filterColumn('features', function ($query, $keyword) {
                $query->whereTranslationLike('features', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('name', function (Product $data) {
                $this->useStoreLocale();
            
                // Limita o nome a 50 caracteres e remove tags HTML
                $name = strip_tags($data->name);
                $name = mb_strlen($name, 'utf-8') > 50 ? mb_substr($name, 0, 50, 'utf-8') . '...' : $name;
            
                // Adiciona o link do Mercado Livre se estiver ativo
                if (config('mercadolivre.is_active') && $data->mercadolivre_id) {
                    $ml_id = substr($data->mercadolivre_id, 0, 3) . '-' . substr($data->mercadolivre_id, 3, 10);
                    $name .= '<small> Anúncio Mercado Livre <a target="_blank" href="https://produto.mercadolivre.com.br/' . $ml_id . '">' . $ml_id . '</a> <i class="fas fa-check"></i></small>';
                }
            
                // Links de ID, SKU e REF CODE
                $id_link = '<small>ID: <a href="' . route('front.product', $data->slug) . '?admin-view=true" target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';
                $sku = $data->type === 'Physical' ? '<small class="ml-2"> SKU: <a href="' . route('front.product', $data->slug) . '?admin-view=true" target="_blank">' . $data->sku . '</a></small>' : '';
                $ref_code = '<small class="ml-2"> ' . __('REF CODE') . ': ' . $data->ref_code . '</small>';
            
                // Se o marketplace estiver ativo, adiciona o vendedor
                $vendor = '';
                if (config('features.marketplace') && $data->user_id != 0 && count($data->user->products) > 0) {
                    $vendor = '<small class="ml-2"> ' . __('VENDOR') . ': <a href="' . route('admin-vendor-show', $data->user_id) . '" target="_blank">' . $data->user->shop_name . '</a></small>';
                }
            
                // Botão de edição rápida
                $fast_edit_btn = '<a title="' . __("Edit") . '" data-href="' . route('admin-prod-fastedit', $data->id) . '" 
                    class="fasteditbtn" data-header="' . __("Edit") . " " . $data->ref_code . '" 
                    data-toggle="modal" data-target="#fast_edit_modal">
                    <i class="fas fa-edit text-primary"></i>
                </a>';
            
                // Indica se há produtos associados
                if ($data->associatedProducts()->count() > 0) {
                    $name .= ' *';
                }
            
                $this->useAdminLocale();
            
                return $fast_edit_btn . $name . '<br>' . $id_link . $sku . $ref_code . $vendor;
            })            

            ->editColumn('features', function (Product $data) {
                return !empty($data->features[1]) ? $data->features[0] . ", " . $data->features[1] : $data->features;
            })

            ->editColumn('price', function (Product $data) {
                $sign = Currency::find(1);
                return $sign->sign . number_format(
                    $data->price * $sign->value, $sign->decimal_digits, $sign->decimal_separator, $sign->thousands_separator
                );
            })            
            ->editColumn('photo', function (Product $data) {
                $thumbnailPath = public_path("storage/images/thumbnails/{$data->thumbnail}");
            
                return file_exists($thumbnailPath) 
                    ? asset("storage/images/thumbnails/{$data->thumbnail}") 
                    : ($this->storeSettings->ftp_folder ? htmlentities($data->thumbnail) : asset('assets/images/noimage.png'));
            })            
            ->editColumn('stock', fn(Product $data) => $data->stock === 0 ? __("Out Of Stock!") : ($data->stock === null ? __("Unlimited") : $data->stock))
            ->addColumn('status', function (Product $data) {
                $checked = $data->status ? 'checked' : '';
                $route = route('admin-prod-status', ['id1' => $data->id, 'id2' => $data->status]);
            
                return <<<HTML
                    <div class="fix-social-links-area social-links-area">
                        <label class="switch">
                            <input type="checkbox" class="droplinks drop-sucess checkboxStatus" 
                                   id="checkbox-status-{$data->id}" name="{$route}" {$checked}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                HTML;
            })            
            ->addColumn('featured', function (Product $data) {
                $route = route('admin-prod-feature', $data->id);
                $title = __('Featured');
                
                return <<<HTML
                    <a data-href="{$route}" class="feature add-btn" data-toggle="modal" 
                       data-target="#modal2" data-header="{$title}">
                        <i class="icofont-star" data-toggle="tooltip" title="{$title}"></i> {$title}
                    </a>
                HTML;
            })            
            ->addColumn('bulk', function (Product $data) {
                $id = $data->id;
            
                return <<<HTML
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="bulk_{$id}" class="custom-control-input product-bulk-check" 
                               id="bulk_{$id}" value="{$id}">
                        <label class="custom-control-label" for="bulk_{$id}"></label>
                    </div>
                HTML;
            })            
            ->rawColumns(['bulk', 'photo', 'action', 'name', 'price', 'stock', 'status', 'featured'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        $filters = [
            "all" => __('All Products'),
            "active" => __('Active Products'),
            "inactive" => __('Inactive Products'),
            "without_image" => __('Without Image'),
            "active_without_image" => __('Active Without Image'),
            "with_tags" => __('With Tags'),
            "without_tags" => __('Without Tags'),
            "without_details" => __('Without Details'),
            "featured" => __('Featured'),
            "latest" => __('Latest'),
            "without_category" => __('Without Category'),
            "system_name" => __('With System Name'),
            "with_image" => __('With Image'),
            "Activate_products_with_image" => __('Active products with image'),
            "Inative_products_with_image" => __('Inative products with image'),
            "Out-of-stock_and_active_products" => __('Out-of-stock and active products'),
            "Stock_and_inactive_products" => __('Stock and inactive products')
        ];
    
        // Carrega todos os dados necessários em uma única consulta para otimizar o tempo de execução
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::find(1); // Usando `find` ao invés de `where()->first()` para uma consulta mais eficiente
        $storesList = Generalsetting::all();
    
        // Retorna a view com os dados compactados
        return view('admin.product.index', compact('filters', 'cats', 'brands', 'sign', 'storesList'));
    }    

    //*** GET Request
    public function types()
    {
        return view('admin.product.types');
    }

    //*** GET Request
    public function createPhysical()
    {
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::find(1);
        $storesList = Generalsetting::all();
        return view('admin.product.create.physical', compact('cats', 'sign', 'brands', 'storesList'));
    }

    //*** GET Request
    public function createDigital()
    {
        $cats = Category::all();
        $sign = Currency::find(1);
        return view('admin.product.create.digital', compact('cats', 'sign'));
    }

    //*** GET Request
    public function createLicense()
    {
        $cats = Category::all();
        $sign = Currency::find(1);
        return view('admin.product.create.license', compact('cats', 'sign'));
    }

    //*** GET Request
    public function status($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    //*** GET Featured
    public function featured($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->featured = $id2;
        $data->update();
    }

    //*** GET Request
    public function catalog($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->is_catalog = $id2;
    
        // Usando o método `save` em vez de `update` para uma operação mais eficiente
        $data->save();
    
        // Mensagem baseada no valor de $id2
        $msg = $id2 == 1 
            ? __("Product added to catalog successfully.") 
            : __("Product removed from catalog successfully.");
    
        return response()->json($msg);
    }    

    //*** POST Request
    public function uploadUpdate(Request $request, $id)
    {
        //--- Validation Section
        $request->validate([
            'image' => 'required',
        ]);
    
        $data = Product::findOrFail($id);
    
        //--- Process image
        $image = $request->image;
        list(, $image) = explode(',', explode(';', $image)[1]);
        $image = base64_decode($image);
    
        $image_name = time() . Str::random(8) . '.png';
        $path = public_path('storage/images/products/' . $image_name);
        file_put_contents($path, $image);
    
        //--- Remove old photo if exists
        $this->deleteFileIfExists(public_path('storage/images/products/' . $data->photo));
    
        //--- Update photo field
        $data->update(['photo' => $image_name]);
    
        //--- Handle thumbnail
        $this->deleteFileIfExists(public_path('storage/images/thumbnails/' . $data->thumbnail));
    
        $thumbnail = time() . Str::random(8) . '.png';
        $img = Image::make($path)->resize(285, 285);
        $img->save(public_path('storage/images/thumbnails/' . $thumbnail));
    
        //--- Update thumbnail field
        $data->update(['thumbnail' => $thumbnail]);
    
        return response()->json(['status' => true, 'file_name' => $image_name]);
    }
    
    /**
     * Delete a file if it exists.
     */
    private function deleteFileIfExists($filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }    

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            // 'photo'      => 'required',
            'file'       => 'mimes:zip'
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            $response = ['errors' => $validator->getMessageBag()->toArray()];
            $status = $request->api ? Response::HTTP_BAD_REQUEST : Response::HTTP_OK;
            
            return response()->json($response, $status);
        }        
        //--- Validation Section Ends
        //--- Logic Section
        $data = new Product;
        $sign = Currency::find(1);
        $input = $this->withRequiredFields($request->all(), ['name']);
        $input['show_price'] = (bool) $request->show_price ?? false;
        // Check File
        if ($file = $request->file('file')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/files', $name);
            $input['file'] = $name;
        }

        if (!empty($input['photo'])) {
            $image = base64_decode(explode(',', $request->photo)[1]);
            $image_name = time() . Str::random(8) . '.png';
            $path = 'storage/images/products/' . $image_name;
        
            file_put_contents($path, $image);
            $input['photo'] = $image_name;
        }        
        //-- Translations Section
        // Will check each field in language 1 and then for each other language
        // Check Seo
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = join(',', $input[$this->lang->locale]['meta_tag']);
        }        

        // Check Features
        // Verifica se as variáveis são válidas para processamento
        $features = $input[$this->lang->locale]['features'] ?? null;
        $colors = $request->colors ?? null;

        // Se algum dos valores estiver vazio, inicializa como null
        if (empty($features) || empty($colors)) {
            $input[$this->lang->locale]['features'] = $input['colors'] = null;
        } else {
            // Se houver null nas características ou cores, substitui os valores
            $featuresProcessed = array_map('trim', (array)$features);
            $colorsProcessed = array_map('trim', (array)$colors);

            if (in_array(null, $featuresProcessed, true) || in_array(null, $colorsProcessed, true)) {
                $input[$this->lang->locale]['features'] = implode(' ', $featuresProcessed);
                $input['colors'] = implode(' ', $colorsProcessed);
            } else {
                $input[$this->lang->locale]['features'] = implode(',', $featuresProcessed);
                $input['colors'] = implode(',', $colorsProcessed);
            }
        }

        // Preprocessamento de dados para otimização de acesso
        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            // Processamento de 'meta_tag' apenas se não estiver vazio
            $metaTag = $input[$loc->locale]['meta_tag'] ?? null;
            if (!empty($metaTag)) {
                $input[$loc->locale]['meta_tag'] = implode(',', (array)$metaTag);
            }

            // Processamento de 'features' apenas se não estiver vazio
            $features = $input[$loc->locale]['features'] ?? null;
            if (!empty($features)) {
                $featuresProcessed = array_map('trim', (array)$features);
                if (in_array(null, $featuresProcessed, true)) {
                    $input[$loc->locale]['features'] = null;
                } else {
                    $input[$loc->locale]['features'] = implode(',', $featuresProcessed);
                }
            }
        }
        //-- End Translations Section
        // Check Physical
        if ($request->type == "Physical") {
            if ($request->api) {
                $api_rules = [
                    'sku' => 'required|unique:products',
                    'price' => 'required',
                    'ref_code' => 'required'
                ];
                $validator = Validator::make($request->all(), $api_rules);
                if ($validator->fails()) {
                    return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
                }
            }

            //--- Validation Section
            $rules = [
                'sku'      => 'min:1|unique:products',
                'ref_code'      => 'max:50|unique:products',
                'mpn'      => 'max:50'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends
            // Check Condition
            if ($request->product_condition_check == "") {
                $input['product_condition'] = 0;
            }

            // Check Shipping Time
            if ($request->shipping_time_check == "") {
                $input['ship'] = null;
            }

            // Check Size
            if (empty($request->size_check)) {
                $input['size'] = null;
                $input['size_qty'] = null;
                $input['size_price'] = null;
            } else {
                if (in_array(null, $request->size) || in_array(null, $request->size_qty)) {
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                } else {
                    foreach ($request->size as $key => $size) {
                        $size_without_comma[$key] = str_replace(',', '.', $size);
                    }
                    $input['size'] = implode(',', $size_without_comma);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $input['size_price'] = implode(',', $request->size_price);
                    $stck = 0;
                    foreach ($request->size_qty as $key => $size) {
                        $stck += (int)$request->size_qty[$key];
                    }
                    $input['stock'] = $stck;
                }
            }

            // Check Whole Sale
            if (empty($request->whole_check)) {
                $input['whole_sell_qty'] = null;
                $input['whole_sell_discount'] = null;
            } else {
                if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
                    $input['whole_sell_qty'] = null;
                    $input['whole_sell_discount'] = null;
                } else {
                    $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                    $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                }
            }

            // Check Color
            if (empty($request->color_check)) {
                $input['color'] = null;
                $input['color_qty'] = null;
                $input['color_price'] = null;
            } else {
                if (in_array(null, $request->color) || in_array(null, $request->color_qty)) {
                    $input['color'] = null;
                    $input['color_qty'] = null;
                    $input['color_price'] = null;
                } else {
                    $input['color'] = implode(',', $request->color);
                    $input['color_qty'] = implode(',', $request->color_qty);
                    $input['color_price'] = implode(',', $request->color_price);
                    $stck = 0;
                    foreach ($request->color_qty as $key => $color) {
                        $stck += (int)$request->color_qty[$key];
                    }
                    $input['stock'] = $stck;

                    $input['color_gallery'] = null;

                    // Color Gallery
                    if ($files_arr = $request->file('color_gallery')) {
                        foreach ($files_arr as  $key => $file_arr) {
                            foreach ($file_arr as $key => $file) {
                                $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                $input['color_gallery'] .= $name . "|";
                                $file->move('storage/images/color_galleries', $name);
                            }
                            $input['color_gallery'] = substr_replace($input['color_gallery'], "", -1);
                            $input['color_gallery'] .= ",";
                        }
                        $input['color_gallery'] = substr_replace($input['color_gallery'], "", -1);
                    }
                }
            }

            if (empty($request->material_check)) {
                $input['material'] = null;
                $input['material_gallery'] = null;
                $input['material_qty'] = null;
                $input['material_price'] = null;
            } else {
                if (in_array(null, $request->material) || in_array(null, $request->material_qty)) {
                    $input['material'] = null;
                    $input['material_qty'] = null;
                    $input['material_price'] = null;
                    $input['material_gallery'] = null;
                } else {
                    $input['material'] = implode(",", $request->material);
                    $input['material_qty'] = implode(',', $request->material_qty);
                    $input['material_price'] = implode(',', $request->material_price);
                    $input['material_gallery'] = null;
                    $stck = 0;
                    foreach ($request->material_qty as $key => $material) {
                        $stck += (int)$request->material_qty[$key];
                    }
                    $input['stock'] = $stck;
                    if ($files_arr = $request->file('material_gallery')) {
                        foreach ($files_arr as $key => $file_arr) {
                            foreach ($file_arr as $key => $file) {
                                $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                $input['material_gallery'] .= $name . "|";
                                $file->move("storage/images/material_galleries", $name);
                            }
                            $input['material_gallery'] = substr_replace($input['material_gallery'], "", -1);
                            $input['material_gallery'] .= ",";
                        }
                        $input['material_gallery'] = substr_replace($input['material_gallery'], "", -1);
                    }
                }
            }

            // Check Measurement
            if ($request->measure_check == "") {
                $input['measure'] = null;
            }
        }

        // Check License
        if ($request->type == "License") {
            // Verificar se os arrays possuem apenas valores válidos (não nulos)
            $valid_license = array_filter($request->license, function($value) { return $value !== null; });
            $valid_license_qty = array_filter($request->license_qty, function($value) { return $value !== null; });
        
            // Se algum dos arrays estiver vazio, definir as variáveis como null
            if (empty($valid_license) || empty($valid_license_qty)) {
                $input['license'] = null;
                $input['license_qty'] = null;
            } else {
                // Caso contrário, fazer o "implode" apenas dos valores válidos
                $input['license'] = implode(',,', $valid_license);
                $input['license_qty'] = implode(',', $valid_license_qty);
            }
        }        
        // Conert Price According to Currency
        // Realiza a divisão de forma eficiente
        $input['price'] = (float) $input['price'] / $sign->value;
        $input['previous_price'] = (float) $input['previous_price'] / $sign->value;

        // Filtragem dos atributos de produto físico
        $attrArr = [];

        // Verificar se a categoria existe no request
        if ($request->has('category_id') && !empty($request->category_id)) {
            // Carregar os atributos da categoria de forma eficiente
            $catAttrs = Attribute::where('attributable_id', $request->category_id)
                                ->where('attributable_type', 'App\Models\Category')
                                ->get(['input_name', 'details_status']); // Selecionando apenas os campos necessários

            // Utilizar um array associativo para melhorar a performance de busca de dados
            $requestAttributes = $request->only(array_map(fn($attr) => "attr_$attr", array_column($catAttrs->toArray(), 'input_name')));

            // Processar os atributos da categoria de forma eficiente
            foreach ($catAttrs as $catAttr) {
                $in_name = $catAttr->input_name;
                
                // Verificar se o atributo existe no request
                if (isset($requestAttributes["attr_$in_name"])) {
                    $attrArr[$in_name] = [
                        'values' => $request["attr_$in_name"],
                        'prices' => $request["attr_{$in_name}_price"],
                        'details_status' => (int) $catAttr->details_status // Convertendo para inteiro diretamente
                    ];
                }
            }
        }

        if (!empty($request->subcategory_id)) {
            // Otimizando a consulta com pluck() para buscar apenas 'input_name' e 'details_status'
            $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)
                                ->where('attributable_type', 'App\Models\Subcategory')
                                ->pluck('input_name', 'details_status'); // Pluck de apenas o necessário
        
            // Verificando se temos atributos para processar
            if ($subAttrs->isNotEmpty()) {
                // Coletando os dados necessários do request de uma vez para evitar chamadas repetidas
                foreach ($subAttrs as $input_name => $details_status) {
                    // Verificando se a chave existe no request uma única vez
                    if ($request->has("attr_$input_name")) {
                        // Atribuindo os valores
                        $attrArr[$input_name] = [
                            'values' => $request->input("attr_$input_name"),
                            'prices' => $request->input("attr_{$input_name}_price"),
                            'details_status' => $details_status ? 1 : 0,  // Definindo o status diretamente
                        ];
                    }
                }
            }
        }
        
        if (!empty($request->childcategory_id)) {
            // Utilizando pluck() para trazer apenas o necessário: 'input_name' e 'details_status'
            $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)
                                    ->where('attributable_type', 'App\Models\Childcategory')
                                    ->pluck('input_name', 'details_status');  // Pluck otimizado
        
            // Verificando se a coleção tem atributos para processar
            if ($childAttrs->isNotEmpty()) {
                // Coletando os dados necessários do request fora do loop
                foreach ($childAttrs as $input_name => $details_status) {
                    // Verificando se a chave existe no request uma única vez
                    if ($request->has("attr_$input_name")) {
                        // Atribuindo os valores diretamente ao array
                        $attrArr[$input_name] = [
                            'values' => $request->input("attr_$input_name"),
                            'prices' => $request->input("attr_{$input_name}_price"),
                            'details_status' => $details_status ? 1 : 0,  // Definindo o status diretamente
                        ];
                    }
                }
            }
        }        
        // Verificando se o array não está vazio
        $input['attributes'] = !empty($attrArr) ? json_encode($attrArr) : null;

        // Save Data
        $data->fill($input)->save();

        // Get associated data
        $associated_colors = $request->input('associated_colors', []);
        $associated_sizes = $request->input('associated_sizes', []);
        $associated_looks = $request->input('associated_looks', []);

        // Detach all associated products before attaching
        $prod = Product::find($data->id);
        $prod->associatedProducts()->detach();

        // Prepare data for association
        $associations = [];

        if ($associated_colors) {
            foreach ($associated_colors as $color) {
                $associations[] = [
                    'product_id' => $prod->id,
                    'associated_product_id' => $color,
                    'association_type' => AssociationType::Color->value
                ];
            }
        }

        if ($associated_sizes) {
            foreach ($associated_sizes as $size) {
                $associations[] = [
                    'product_id' => $prod->id,
                    'associated_product_id' => $size,
                    'association_type' => AssociationType::Size->value
                ];
            }
        }

        if ($associated_looks) {
            foreach ($associated_looks as $look) {
                $associations[] = [
                    'product_id' => $prod->id,
                    'associated_product_id' => $look,
                    'association_type' => AssociationType::Look->value
                ];
            }
        }

        // Bulk insert to the associated_products table
        if (!empty($associations)) {
            FacadeDB::table('associated_products')->insert($associations);
        }

        // Handle inverse associations for color
        if ($associated_colors) {
            $colorAssociations = [];
            foreach ($associated_colors as $color) {
                // Add inverse association for each color
                $colorAssociations[] = [
                    'product_id' => $color,
                    'associated_product_id' => $prod->id,
                    'association_type' => AssociationType::Color->value
                ];
            }

            // Bulk insert for inverse associations
            if (!empty($colorAssociations)) {
                FacadeDB::table('associated_products')->insert($colorAssociations);
            }
        }

        // Validate Redplay
        if ($request->redplay_login && $request->redplay_password && $request->redplay_code) {
            $redplayData = Product::sanitizeRedplayData([
                'redplay_login' => $request->redplay_login,
                'redplay_password' => $request->redplay_password,
                'redplay_code' => $request->redplay_code
            ]);

            // Filtra dados vazios diretamente, sem precisar de loop extra
            $redplayData = array_filter($redplayData, function ($redplay) {
                return $redplay['login'] && $redplay['password'] && $redplay['code'];
            });

            // Coleta todos os códigos para evitar múltiplas consultas ao banco
            $codes = array_column($redplayData, 'code');
            $existingLicenses = License::whereIn('code', $codes)->get()->keyBy('code');

            // Prepara os dados para inserção/atualização em massa
            $licensesToSave = [];
            foreach ($redplayData as $redplay) {
                $license = $existingLicenses[$redplay['code']] ?? new License;

                // Preenche os dados da licença
                $license->product_id = $data->id;
                $license->login = $redplay['login'];
                $license->password = $redplay['password'];
                $license->code = $redplay['code'];

                // Adiciona à lista para salvar em massa
                $licensesToSave[] = $license;
            }

            // Salva ou atualiza todas as licenças de uma vez
            License::upsert($licensesToSave, ['code'], ['product_id', 'login', 'password']);
        }
        // Gerar o slug base
        $slugBase = Str::slug($data->name, '-');

        // Se o tipo do produto não for 'Physical', gerar um slug diferente com randomização
        if ($prod->type != 'Physical') {
            $prod->slug = $slugBase . '-' . strtolower(Str::random(3) . $data->id . Str::random(3));
        } else {
            // Se for 'Physical', usar o SKU
            $prod->slug = $slugBase . '-' . strtolower(Str::slug($data->sku));
        }

        // Atualiza o produto no banco
        $prod->save();

        if (!empty($input['photo'])) {
            // Definir o caminho base
            $basePath = public_path() . '/storage/images/';
            $thumbnailPath = $basePath . 'thumbnails/';
        
            // Criar a imagem redimensionada
            $img = Image::make($basePath . 'products/' . $input['photo']);
            $thumbnail = time() . Str::random(8) . '.jpg';
            $img->resize(285, 285)->save($thumbnailPath . $thumbnail);
        
            // Atribuir o thumbnail ao produto e salvar
            $prod->thumbnail = $thumbnail;
            $prod->save();  // Usar save ao invés de update
        }
        
        $associatedProducts = $prod->associatedProducts()
        ->where('association_type', AssociationType::Size)
        ->get();

        $associatedProductIds = $associatedProducts->pluck('id');  // Pega apenas os IDs

        if ($associatedProductIds->isNotEmpty()) {
            // Atualiza todos os produtos associados de uma vez
            Product::whereIn('id', $associatedProductIds)
                ->update([
                    'photo' => $prod->photo,
                    'thumbnail' => $prod->thumbnail
                ]);
        }        

        // Add To Gallery If any    
        $lastid = $data->id;
        $galleryData = [];
        
        if ($files = $request->file('gallery')) {
            foreach ($files as $key => $file) {
                if (in_array($key, $request->galval)) {
                    $name = time() . $file->getClientOriginalName();
                    $file->move('storage/images/galleries', $name);
        
                    // Armazena os dados para inserção em massa
                    $galleryData[] = [
                        'photo' => $name,
                        'product_id' => $lastid,
                        'created_at' => now(),  // Para garantir a data de criação
                        'updated_at' => now()   // Para garantir a data de atualização
                    ];
                }
            }
        
            // Inserção em massa
            if (!empty($galleryData)) {
                Gallery::insert($galleryData);
            }
        }        
        // Add To Gallery 360 If any
        $lastid = $data->id;
        $gallery360Data = [];
        
        if ($files = $request->file('gallery360')) {
            foreach ($files as $key => $file) {
                if (in_array($key, $request->galval)) {
                    $name = time() . $file->getClientOriginalName();
                    $file->move('storage/images/galleries360', $name);
        
                    // Armazena os dados para inserção em massa
                    $gallery360Data[] = [
                        'photo' => $name,
                        'product_id' => $lastid,
                        'created_at' => now(),  // Definir o timestamp de criação
                        'updated_at' => now()   // Definir o timestamp de atualização
                    ];
                }
            }
        
            // Inserção em massa
            if (!empty($gallery360Data)) {
                Gallery360::insert($gallery360Data);
            }
        }        

        //associates with stores
        if (!empty($input['stores'])) {
            $prod->stores()->sync($input['stores']);
        }

        // Valida e cria a licença Redplay, se fornecida
        if (!empty($request->redplay_license)) {
            License::updateOrCreate(
                ['product_id' => $prod->id], 
                ['data' => $request->redplay_license]
            );
        }

        //logic Section Ends
        //--- Redirect Section
        if ($request->has('bulk_form')) {
            return response()->json(['bulk_store' => true]);
        }
        
        if ($request->api) {
            return response()->json(['status' => 'ok'], Response::HTTP_CREATED);
        }        

        session()->flash('success', __('New Product Added Successfully.'));

        return response()->json(['redirect' => route('admin-prod-index')]);
        //--- Redirect Section Ends
    }

    //*** POST Request
    public function import()
    {
        $cats = Category::all();
        $sign = Currency::find(1);
        return view('admin.product.productcsv', compact('cats', 'sign'));
    }

    public function prepareImport(Request $request)
    {
        $rules = ['csvfile' => 'required|mimes:csv,txt'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    
        // Upload do arquivo
        $file = $request->file('csvfile');
        $filename = time() . '-' . $file->getClientOriginalName();
        $file->storeAs('temp_files', $filename); // Usa o método store
    
        // Leitura do arquivo
        $row = 0;
        if (($fp = fopen(storage_path('app/temp_files/' . $filename), "r")) !== false) {
            while (fgetcsv($fp) !== false) {
                $row++;
            }
            fclose($fp); // Fechar arquivo após leitura
        }
    
        return response()->json([
            'rows' => $row - 1, // Desconsiderando o cabeçalho
            'fileName' => $filename,
            'message' => __("Preparing to insert new data, line:")
        ]);
    }

    public function importSubmit(Request $request)
    {
        $filename = $request->fileName;
        $offset = $request->offset;
        $line = $offset + 2;
        $updateCheck = $request->updateCheck;
    
        // Ler CSV
        $csv = Reader::createFromPath(public_path('storage/temp_files/' . $filename), 'r');
        $csv->setHeaderOffset(0); // set the CSV header offset
    
        // Obter uma linha específica (por offset)
        $stmt = (new Statement())->offset($offset)->limit(1);
        $records = $stmt->process($csv);
    
        $headers = $records->getHeader(); // Carregar cabeçalhos uma vez
    
        foreach ($records as $record) {
            if (!is_array($record) || count($record) < 2) {
                return response()->json(['error' => __('Insert a valid File')], 400);
            }
    
            // Validação de campos obrigatórios
            foreach ($headers as $header) {
                if (strpos($header, '*') !== false && empty($record[$header])) {
                    return response()->json([
                        'errors' => __("The field: ") . $header . __(' in line: ') . $line . __(' cannot be empty')
                    ]);
                }
            }
    
            // Validação do registro
            $record = $this->validateRecord($record);
    
            // Consultas mais eficientes
            $product = Product::where('sku', $record['sku'])->first();
    
            if ($product || !$updateCheck) {
                $record['type'] = 'Physical';
                $record['previous_price'] = 0;
                $record['stock'] = $record['stock'] ?: null;
    
                // Carregar traduções e dados relacionados de uma vez
                $category = CategoryTranslation::where(DB::raw('lower(name)'), strtolower($record['category_id']))->first();
                if ($category) {
                    $record['category_id'] = $category->category_id;
    
                    $subcategory = $record['subcategory_id'] ? SubcategoryTranslation::where(DB::raw('lower(name)'), strtolower($record['subcategory_id']))->first() : null;
                    $record['subcategory_id'] = $subcategory ? $subcategory->subcategory_id : null;
    
                    $childcategory = $record['childcategory_id'] ? ChildcategoryTranslation::where(DB::raw('lower(name)'), strtolower($record['childcategory_id']))->first() : null;
                    $record['childcategory_id'] = $childcategory ? $childcategory->childcategory_id : null;
    
                    $brand = $record['brand_id'] ? Brand::where(DB::raw('lower(name)'), strtolower($record['brand_id']))->first() : null;
                    $record['brand_id'] = $brand ? $brand->id : null;
    
                    $record[$this->lang->locale]['name'] = $record['name'];
                    $record[$this->lang->locale]['details'] = $record['details'];
                    $record[$this->lang->locale]['features'] = [null];
                    $record['price'] = $record['price'] ?: 0;
                    $record['stores'] = [1];
                    $record['bulk_form'] = true;
    
                    $request->replace($record);
    
                    // Atualizar ou criar
                    if ($product && $updateCheck) {
                        return $this->update($request, $product->id);
                    }
                    return $this->store($request);
                } else {
                    return response()->json([
                        'errors' => __("No Category Found!") . __('in line') . ": " . $line
                    ]);
                }
            } else {
                return response()->json([
                    'errors' => __("Duplicate Product Code! in line: ") . $line
                ]);
            }
        }
    }

    public function endImport(Request $request)
    {
        $filename = $request->fileName;
    
        // Remover arquivo com menor impacto de performance
        @unlink(public_path('storage/temp_files/' . $filename));
    
        // Criar resposta de forma eficiente
        $msg = [
            'message' => __('Bulk Product File Imported Successfully.') . 
                ' <a href="' . route('admin-prod-index') . '">' . __('View Product Lists.') . '</a>',
            'total_inserted' => '<span class="insertCount"></span>',
            'total_updated' => '<span class="updateCount"></span>',
            'total_errors' => '<span class="errorCount"></span>',
        ];
    
        return response()->json($msg);
    }
    
    private function validateRecord($record)
    {
        // Definir o mapeamento das chaves
        $newKey = [
            "sku*" => "sku",
            "category*" => "category_id",
            "subcategory" => "subcategory_id",
            "childcategory" => "childcategory_id",
            "brand" => "brand_id",
            "product name*" => "name",
        ];
    
        // Renomear as chaves no array
        return $this->renameRecord($record, $newKey);
    }
    
    private function renameRecord($record, $newKey)
    {
        foreach ($newKey as $key => $value) {
            if (isset($record[$key])) {  // Verificar se a chave existe antes de renomear
                $record[$value] = $record[$key];
                // Não usamos unset aqui, apenas substituímos os valores
            }
        }
        return $record;
    }    

    private function cleanEmptyValues($record)
    {
        foreach ($record as $key => $value) {
            if (empty($value)) {
                unset($record[$key]);
            }
        }
        return $record;
    }

    //*** GET Request
    public function edit($id)
    {
        $product = Product::with([
            'category', 
            'subcategory', 
            'childcategory', 
            'stores', 
            'associatedProductsByColor', 
            'associatedProductsBySize', 
            'associatedProductsByLook'
        ])->find($id);
    
        if (!$product) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
    
        // Carregar os dados necessários em conjunto com o produto
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::find(1);
        $storesList = Generalsetting::all();
        
        // Atributos
        $selectedAttrs = json_decode($product->attributes, true);
        $catAttributes = $product->category->attributes ?? '';
        $subAttributes = $product->subcategory->attributes ?? '';
        $childAttributes = $product->childcategory->attributes ?? '';
    
        // Associações
        $currentStores = $product->stores()->pluck('id')->toArray();
        $associatedColors = $product->associatedProductsByColor->pluck('id')->toArray();
        $associatedSizes = $product->associatedProductsBySize->pluck('id')->toArray();
        $associatedLooks = $product->associatedProductsByLook->pluck('id')->toArray();
    
        // Caminho FTP e galerias
        $ftp_path = public_path('storage/images/ftp/' . $this->storeSettings->ftp_folder . $product->ref_code_int . '/');
        $ftp_gallery = [];
        if (is_dir($ftp_path)) {
            $files = array_filter(scandir($ftp_path), function($file) use ($ftp_path) {
                return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'gif', 'png']);
            });
            foreach ($files as $file) {
                $ftp_gallery[] = asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $product->ref_code_int . '/' . $file);
            }
        }
    
        // Retornar a view de acordo com o tipo de produto
        $viewData = [
            'cats' => $cats,
            'brands' => $brands,
            'sign' => $sign,
            'storesList' => $storesList,
            'currentStores' => $currentStores,
            'ftp_gallery' => $ftp_gallery,
            'associatedColors' => $associatedColors,
            'associatedSizes' => $associatedSizes,
            'associatedLooks' => $associatedLooks
        ];
    
        switch ($product->type) {
            case 'Digital':
                return view('admin.product.edit.digital', array_merge($viewData, ['data' => $product]));
            case 'License':
                return view('admin.product.edit.license', array_merge($viewData, ['data' => $product]));
            default:
                return view('admin.product.edit.physical', array_merge($viewData, [
                    'data' => $product,
                    'selectedAttrs' => $selectedAttrs,
                    'catAttributes' => $catAttributes,
                    'childAttributes' => $childAttributes,
                    'subAttributes' => $subAttributes
                ]));
        }
    }    

    public function editMeli($id)
    {
        // Busca o produto e verifica se existe
        $data = Product::find($id);
    
        if (!$data) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
    
        // Armazenando informações do Mercado Livre em cache (se aplicável)
        $meli_category_id = null;
        $meli_category_attributes = null;
        $warranties = [];
        $listingTypesWithDetails = [];
    
        // Evitar chamadas repetidas ao Mercado Livre
        if ($data->mercadolivre_name) {
            $meli_category_id = MercadoLivre::getCategoryId($data->mercadolivre_name);
            
            // Cache da categoria de atributos para evitar múltiplas chamadas
            $meli_category_attributes = MercadoLivre::getCategoryAttributes($meli_category_id);
            $meli_category_attributes = json_decode($meli_category_attributes);
    
            // Cache das garantias, se já obtidas
            $warranties = MercadoLivre::getWarranties($meli_category_id);
    
            // Determina a garantia "sem garantia"
            $withoutWarrantyId = $this->getWithoutWarrantyId($warranties);
    
            // Cache dos tipos de anúncio
            $listingTypes = MercadoLivre::getListingTypes();
            $listingTypesWithDetails = $this->getListingTypesWithDetails($listingTypes);
        }
    
        // Processar atributos de categoria
        $selectedCategoryAttributes = json_decode($data->mercadolivre_category_attributes);
    
        $extraData = [
            'meli_category_attributes' => $this->mergeCategoryAttributes($meli_category_attributes, $selectedCategoryAttributes)
        ];
    
        return view('admin.product.edit.meli', compact('data', 'extraData', 'listingTypesWithDetails', 'warranties', 'withoutWarrantyId'));
    }
    
    // Função para buscar o ID da garantia "sem garantia"
    private function getWithoutWarrantyId($warranties)
    {
        foreach ($warranties as $warranty) {
            if (isset($warranty['values']) && $warranty['id'] === "WARRANTY_TYPE") {
                foreach ($warranty['values'] as $warrantyType) {
                    if ($warrantyType->name === "Sem garantia") {
                        return $warrantyType->id;
                    }
                }
            }
        }
        return null;
    }
    
    // Função para processar e mesclar os atributos de categoria
    private function mergeCategoryAttributes($meli_category_attributes, $selectedCategoryAttributes)
    {
        foreach ($meli_category_attributes as $key => $categoryAttribute) {
            if (isset($selectedCategoryAttributes->$key->value)) {
                $categoryAttribute->value = $selectedCategoryAttributes->$key->value;
            }
    
            if (isset($selectedCategoryAttributes->$key->allowed_unit_selected)) {
                foreach ($categoryAttribute->allowed_units as $allowedUnit) {
                    $allowedUnit->selected = ($allowedUnit->name === $selectedCategoryAttributes->$key->allowed_unit_selected);
                }
                $categoryAttribute->selected_unit = $selectedCategoryAttributes->$key->allowed_unit_selected;
            }
        }
        return $meli_category_attributes;
    }
    
    // Função para obter os tipos de anúncio com detalhes
    private function getListingTypesWithDetails($listingTypes)
    {
        $listingTypesWithDetails = [];
        foreach ($listingTypes as $listingType) {
            $listingTypesWithDetails[$listingType->id] = [
                'site_id' => $listingType->site_id,
                'id' => $listingType->id,
                'name' => $listingType->name,
                'details' => MercadoLivre::getListingTypeDetail($listingType->id)
            ];
    
            // Mapeamento dos tipos de exposição
            $listingTypesWithDetails[$listingType->id]['details']['configuration']->listing_exposure = $this->mapExposureLevel(
                $listingTypesWithDetails[$listingType->id]['details']['configuration']->listing_exposure
            );
        }
    
        return $listingTypesWithDetails;
    }
    
    // Função para mapear o nível de exposição do anúncio
    private function mapExposureLevel($exposure)
    {
        $exposureMap = [
            'lowest' => 'Baixíssima',
            'low' => 'Baixa',
            'mid' => 'Regular',
            'high' => 'Alta',
            'highest' => 'Altíssima'
        ];
    
        return $exposureMap[$exposure] ?? $exposure;
    }    

    //*** GET Request
    public function copy($id)
    {
        // Busca o produto diretamente sem consulta desnecessária
        $old = Product::with(['category', 'subcategory', 'childcategory', 'stores'])->find($id);
    
        if (!$old) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
    
        // Obtém os atributos das categorias de forma mais eficiente
        $selectedAttrs = json_decode($old->attributes, true);
        $catAttributes = $old->category->attributes ?? '';
        $subAttributes = $old->subcategory->attributes ?? '';
        $childAttributes = $old->childcategory->attributes ?? '';
    
        // Otimiza a obtenção da moeda e configurações da loja
        $sign = Currency::find(1);
        $storesList = Generalsetting::all(); // Verifique se precisa mesmo buscar todas as configurações
        $currentStores = $old->stores->pluck('id')->toArray();
    
        // Replicando o produto e alterando os campos necessários
        $new = $old->replicateWithTranslations();
        $new->slug = Str::slug($new->name, '-') . '-' . strtolower(Str::random(3) . $new->id . Str::random(3));
        $new->sku = Str::random(3) . substr(time(), 6, 8) . Str::random(3);
        $new->ref_code = $new->sku;
        $new->photo = null;
        $new->thumbnail = null;
    
        // Utilizando `save()` em vez de `Push()` e `update()`, já que `save()` já persiste as alterações
        $new->save();
    
        // Associar com lojas
        if ($old->stores->isNotEmpty()) {
            $new->stores()->sync($old->stores->pluck('id')->toArray());
        }
    
        // Retorna uma mensagem de sucesso
        return response()->json(__('Product Copied Successfully.'));
    }    
    //*** POST Request

    public function update(Request $request, $id)
    {
        // return $request;
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'file'       => 'mimes:zip'
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            if ($request->api) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        //-- Logic Section
        $data = Product::findOrFail($id);
        $associated_colors = $request->input('associated_colors', []);
        $associated_sizes = $request->input('associated_sizes', []);
        $associated_looks = $request->input('associated_looks', []);

        $data->associatedProducts()->detach();
        $data->associatedProducts()->attach($associated_colors, ['association_type' => AssociationType::Color]);
        $data->associatedProducts()->attach($associated_sizes, ['association_type' => AssociationType::Size]);
        $data->associatedProducts()->attach($associated_looks, ['association_type' => AssociationType::Look]);

        if ($associated_colors) {
            // Carregar todos os produtos associados em uma única consulta
            $colorProducts = Product::whereIn('id', $associated_colors)->get();
        
            // Armazenar os IDs dos produtos associados
            $productsAssociatedId = $colorProducts->pluck('id')->toArray();
        
            // Prepare the association data
            $colorAssociations = [];
            $inverseAssociations = [];
        
            foreach ($colorProducts as $colorProduct) {
                // Verificar associação inversa em lote
                if (!$colorProduct->associatedProducts()->where('associated_product_id', $data->id)->where('association_type', AssociationType::Color)->exists()) {
                    // Adicionar associação para o produto atual
                    $colorAssociations[$colorProduct->id] = ['association_type' => AssociationType::Color];
                }
        
                // Adicionar associação para o produto "data"
                $colorAssociations[$data->id] = ['association_type' => AssociationType::Color];
        
                // Gerar associações inversas
                foreach ($productsAssociatedId as $productColorFk) {
                    if ($colorProduct->id != $productColorFk) {
                        $existingInverseAssociation = $colorProduct->associatedProducts()->where('associated_product_id', $productColorFk)->where('association_type', AssociationType::Color)->exists();
                        
                        if (!$existingInverseAssociation) {
                            $inverseAssociations[] = [
                                'product_id' => $colorProduct->id,
                                'associated_product_id' => $productColorFk,
                                'association_type' => AssociationType::Color->value
                            ];
                        }
                    }
                }
            }
        
            // Inserir as associações inversas de uma vez
            if (!empty($inverseAssociations)) {
                FacadeDB::table('associated_products')->insert($inverseAssociations);
            }
        
            // Atualizar as associações com syncWithoutDetaching de uma vez
            $data->associatedProducts()->syncWithoutDetaching($colorAssociations);
        }
        
        $associatedProducts = $data->associatedProducts()->where('association_type', AssociationType::Size)->get();
        
        // Atualizar os produtos associados com a foto e thumbnail
        foreach ($associatedProducts as $associatedProduct) {
            if ($data->photo != null) {
                $associatedProduct->update([
                    'photo' => $data->photo,
                    'thumbnail' => $data->thumbnail,
                ]);
            }
        }
        
        $data->product_size = $request->input('product_size');
        
        // Processar "Back in Stock" apenas quando necessário
        if ($this->storeSettings->is_back_in_stock && $data->stock == 0 && $request->stock > 0) {
            BackInStock::dispatch($data, $this->storeSettings);
        }        

        $sign = Currency::find(1);
        //$input = $this->removeEmptyTranslations($request->all(), $data);
        $input = $this->withRequiredFields($request->except(['photo', 'thumbnail']), ['name']);
        $input['show_price'] = (bool) $request->show_price ?? false;
        //-- Translations Section
        // Will check each field in language 1 and then for each other language
        // Check Seo
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = implode(',', $input[$this->lang->locale]['meta_tag']);
        }

        if (!$request->api) {
            // Verifica se as cores e features estão vazias, definindo-as como nulas se necessário
            $features = $input[$this->lang->locale]['features'] ?? null;
            $colors = $request->colors ?? null;
        
            if (empty($features) || empty($colors)) {
                $input[$this->lang->locale]['features'] = null;
                $input['colors'] = null;
            } else {
                // Verifica se ambos os arrays não possuem valores nulos
                $featuresValid = !in_array(null, $features);
                $colorsValid = !in_array(null, $colors);
        
                if ($featuresValid && $colorsValid) {
                    // Remover vírgulas e substitui por espaços para as features e cores
                    $input[$this->lang->locale]['features'] = implode(',', str_replace(',', ' ', $features));
                    $input['colors'] = implode(',', str_replace(',', ' ', $colors));
                } else {
                    // Caso algum valor seja nulo, substitui por espaços em branco
                    $input[$this->lang->locale]['features'] = implode('', str_replace('', ' ', $features ?? []));
                    if (!empty($colors)) {
                        $input['colors'] = implode(',', str_replace(',', ' ', $colors));
                    }
                }
            }
        }        

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }
        
            $localeData = &$input[$loc->locale]; // Referência direta ao array do locale
        
            // Verifica se 'meta_tag' existe e não é vazio, então faz o implode.
            if (!empty($localeData['meta_tag'])) {
                $localeData['meta_tag'] = implode(',', $localeData['meta_tag']);
            }
        
            // Verifica se 'features' existe e não é vazio, processa se necessário
            if (!empty($localeData['features'])) {
                $features = $localeData['features'];
        
                // Verifica se não existe valor nulo
                if (!in_array(null, $features)) {
                    $localeData['features'] = implode(',', str_replace(',', ' ', $features));
                } else {
                    // Se existir valor nulo, zera o valor
                    $localeData['features'] = null;
                }
            }
        }        
        //-- End of Translations Section
        //Check Types
        if ($request->type_check == 1) {
            $input['link'] = null;
        } else {
            if ($data->file != null) {
                if (file_exists(public_path() . '/storage/files/' . $data->file)) {
                    unlink(public_path() . '/storage/files/' . $data->file);
                }
            }
            $input['file'] = null;
        }

        // Check Physical
        if ($data->type == "Physical") {
            //--- Validation Section
            $rules = [
                'sku' => 'min:1|unique:products,sku,' . $id,
                'ref_code' => 'max:50|unique:products,ref_code,' . $id,
                'mpn'      => 'max:50'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                if ($request->api) {
                    return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
                }
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends
            // Check Size
            if (empty($request->size_check) || in_array(null, $request->size, true) || in_array(null, $request->size_qty, true) || in_array(null, $request->size_price, true)) {
                $input['size'] = $input['size_qty'] = $input['size_price'] = null;
            } else {
                $input['size'] = implode(',', array_map(fn($size) => str_replace(',', '.', $size), $request->size));
                $input['size_qty'] = implode(',', $request->size_qty);
                $input['size_price'] = implode(',', $request->size_price);
                $input['stock'] = array_sum(array_map('intval', $request->size_qty));
            }            

                // Helper function to process attributes
                function processAttributes($request, $type) {
                    $input = [
                        $type => null, 
                        $type . '_qty' => null, 
                        $type . '_price' => null, 
                        $type . '_gallery' => null
                    ];

                    if (!empty($request[$type . '_check']) && !in_array(null, $request[$type]) && !in_array(null, $request[$type . '_qty'])) {
                        $input[$type] = implode(',', $request[$type]);
                        $input[$type . '_qty'] = implode(',', $request[$type . '_qty']);
                        $input[$type . '_price'] = implode(',', $request[$type . '_price'] ?? []);

                        $input['stock'] = array_sum(array_map('intval', $request[$type . '_qty']));
                        $input[$type . '_gallery'] = processGallery($request, $type);
                    }
                    
                    return $input;
                }

                // Helper function to process gallery images
                function processGallery($request, $type) {
                    $gallery = [];

                    if ($files_arr = $request->file($type . '_gallery')) {
                        foreach ($files_arr as $key => $file_arr) {
                            foreach ($file_arr as $file) {
                                $name = time() . Str::random(8) . '.' . $file->getClientOriginalExtension();
                                $gallery[] = $name;
                                $file->move("storage/images/{$type}_galleries", $name);
                            }
                        }
                    }

                    return !empty($gallery) ? implode(',', $gallery) : ($request[$type . '_gallery_current'] ? implode(',', $request[$type . '_gallery_current']) : null);
                }

                // Main block of code
                if (!$request->api) {
                    $input['free_shipping'] = $request->free_shipping ?: null;
                    $input['product_condition'] = $request->product_condition_check ?: 0;
                    $input['ship'] = $request->shipping_time_check ?: null;
                    $input['measure'] = $request->measure_check ?: null;

                    // Whole Sale Check
                    if (!empty($request->whole_check) && !in_array(null, $request->whole_sell_qty) && !in_array(null, $request->whole_sell_discount)) {
                        $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                        $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                    } else {
                        $input['whole_sell_qty'] = $input['whole_sell_discount'] = null;
                    }

                    // Process color and material attributes
                    $colorAttributes = processAttributes($request, 'color');
                    $materialAttributes = processAttributes($request, 'material');

                    // Merge attributes into the main input
                    $input = array_merge($input, $colorAttributes, $materialAttributes);
                }
        }

        // Check License
        if ($data->type === "License") {
            if (!empty($request->license) && !empty($request->license_qty) && !in_array(null, $request->license) && !in_array(null, $request->license_qty)) {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            } else {
                $input['license'] = null;
                $input['license_qty'] = null;
            }
        }        

        $input['price'] = (floatval($input['price']) / $sign->value);
        $input['previous_price'] = (floatval($input['previous_price']) / $sign->value);

        // store filtering attributes for physical product
        $attrArr = [];
        if (!empty($request->category_id) && Attribute::where('attributable_id', $request->category_id)
                ->where('attributable_type', 'App\Models\Category')->exists()) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)
                ->where('attributable_type', 'App\Models\Category')
                ->get(['input_name', 'details_status']);
        
            foreach ($catAttrs as $catAttr) {
                $in_name = $catAttr->input_name;
                $attr_key = "attr_{$in_name}";
        
                if ($request->has($attr_key)) {
                    $attrArr[$in_name] = [
                        "values" => $request->$attr_key,
                        "prices" => $request->input("{$attr_key}_price"),
                        "details_status" => $catAttr->details_status ? 1 : 0
                    ];
                }
            }
        }        

                if (!empty($request->subcategory_id) && Attribute::where('attributable_id', $request->subcategory_id)
                ->where('attributable_type', 'App\Models\Subcategory')->exists()) {
            
            $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)
                ->where('attributable_type', 'App\Models\Subcategory')
                ->get(['input_name', 'details_status']);

            foreach ($subAttrs as $subAttr) {
                $in_name = $subAttr->input_name;
                $attr_key = "attr_{$in_name}";

                if ($request->has($attr_key)) {
                    $attrArr[$in_name] = [
                        "values" => $request->$attr_key,
                        "prices" => $request->input("{$attr_key}_price"),
                        "details_status" => $subAttr->details_status ? 1 : 0
                    ];
                }
            }
        }

        if (!empty($request->childcategory_id) && Attribute::where('attributable_id', $request->childcategory_id)
        ->where('attributable_type', 'App\Models\Childcategory')->exists()) {
    
            $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)
                ->where('attributable_type', 'App\Models\Childcategory')
                ->get(['input_name', 'details_status']);

            foreach ($childAttrs as $childAttr) {
                $in_name = $childAttr->input_name;
                $attr_key = "attr_{$in_name}";

                if ($request->has($attr_key)) {
                    $attrArr[$in_name] = [
                        "values" => $request->$attr_key,
                        "prices" => $request->input("{$attr_key}_price"),
                        "details_status" => $childAttr->details_status ? 1 : 0
                    ];
                }
            }
        }

        if (empty($attrArr)) {
            $input['attributes'] = null;
        } else {
            $jsonAttr = json_encode($attrArr);
            $input['attributes'] = $jsonAttr;
        }
        $data->update($input);
        $data = Product::findOrFail($id);

        if ($this->storeSettings->ftp_folder) {
            $new_slug = $data->slug;
        } else{
            $new_slug = Str::slug($data->name, '-') . '-' . strtolower(Str::slug($data->sku));
        }

        if (config("features.marketplace") && Product::where('slug', $data->slug)->where('user_id', '!=', 0)->exists()) {
            $vendor_products = Product::where('slug', $data->slug)->where('user_id', '!=', 0)->get();
        
            foreach ($vendor_products as $v_prod) {
                // Adiciona os valores preservados ao input antes de atualizar
                $input['sku'] = $v_prod->sku;
                $input['ref_code'] = $v_prod->ref_code;
                $input['price'] = $v_prod->price;
                $input['slug'] = $new_slug;
        
                // Atualiza os dados do produto de uma vez
                $v_prod->update($input);
            }
        }        

        $data->slug = $new_slug;
        $data->update($input);

        //associates with stores
        $data->stores()->detach();
        if ($request->has('stores')) {
            $data->stores()->sync($input['stores']);
        }

        # Validate Redplay
        if ($request->redplay_login && $request->redplay_password && $request->redplay_code) {
            $redplayData = Product::sanitizeRedplayData([
                'redplay_login' => $request->redplay_login,
                'redplay_password' => $request->redplay_password,
                'redplay_code' => $request->redplay_code
            ]);
        
            # Remove itens vazios diretamente
            $redplayData = array_filter($redplayData, fn($redplay) => $redplay['login'] || $redplay['password'] || $redplay['code']);
        
            if (!empty($redplayData)) {
                # Remove licenças que não estão mais no formulário em uma única query
                License::where('product_id', $data->id)
                    ->whereNotIn('code', array_column($redplayData, 'code'))
                    ->delete();
        
                # Insere ou atualiza licenças de forma otimizada
                foreach ($redplayData as $redplay) {
                    License::updateOrCreate(
                        ['code' => $redplay['code']],
                        [
                            'product_id' => $data->id,
                            'login' => $redplay['login'],
                            'password' => $redplay['password']
                        ]
                    );
                }
            }
        }        

        //-- Logic Section Ends
        //--- Redirect Section
        if ($request->has('bulk_form')) {
            return response()->json([
                'bulk_update' => true
            ]);
            exit;
        }

        if ($request->api) {
            return response()->json(array('status' => 'ok'));
        }

        session()->flash('success', __('Product Updated Successfully.'));
        return response()->json(['redirect' => route('admin-prod-index')]);
        //--- Redirect Section Ends
    }

    public function updateMeli(Request $request, $id)
    {
        //-- Logic Section
        $data = Product::findOrFail($id);
    
        $input = [
            'mercadolivre_name' => $request->mercadolivre_name,
            'mercadolivre_description' => $request->mercadolivre_description,
            'mercadolivre_listing_type_id' => $request->listing_type_id,
            'mercadolivre_price' => $request->mercadolivre_price,
            'mercadolivre_warranty_type_id' => $request->warranty_type_id ?? null,
            'mercadolivre_warranty_type_name' => $request->warranty_type_name ?? null,
            'mercadolivre_warranty_time' => $request->warranty_time ?? null,
            'mercadolivre_warranty_time_unit' => $request->warranty_time_unit ?? null,
            'mercadolivre_without_warranty' => empty($request->warranty_time) && empty($request->warranty_time_unit),
        ];
    
        // Processa atributos da categoria do Mercado Livre de forma otimizada
        if (!empty($request->mercadolivre_category_attributes)) {
            $input['mercadolivre_category_attributes'] = json_encode(array_map(function ($attribute) {
                $key = array_key_first($attribute);
                return [
                    'name' => $key,
                    'value' => $attribute[$key]['value'],
                    'allowed_unit_selected' => $attribute[$key]['allowed_unit_selected'] ?? null,
                ];
            }, $request->mercadolivre_category_attributes));
        }
    
        $data->update($input);
    
        // Redirecionamento otimizado
        return redirect()->route(
            $request->has('update_check') 
                ? ($data->mercadolivre_id ? 'admin-prod-meli-update' : 'admin-prod-meli-send') 
                : 'admin-prod-edit-meli', 
            $data->id
        )->with('success', __('Product Updated Successfully.'));
    }
    
    //*** GET Request
    public function load($id)
    {
        $brand = Brand::findOrFail($id);
        return view('load.brand', compact('brand'));
    }

    //*** GET Request
    public function feature($id)
    {
        $data = Product::findOrFail($id);
        return view('admin.product.highlight', compact('data'));
    }

    //*** POST Request
    public function featuresubmit(Request $request, $id)
    {
        //-- Logic Section
        $data = Product::findOrFail($id);
    
        $input = array_merge($request->only([
            'featured', 'hot', 'best', 'top', 'latest', 
            'big', 'trending', 'sale', 'is_discount', 'discount_date', 'show_in_navbar'
        ]), [
            'featured' => $request->featured ?? 0,
            'hot' => $request->hot ?? 0,
            'best' => $request->best ?? 0,
            'top' => $request->top ?? 0,
            'latest' => $request->latest ?? 0,
            'big' => $request->big ?? 0,
            'trending' => $request->trending ?? 0,
            'sale' => $request->sale ?? 0,
            'is_discount' => $request->is_discount ?? 0,
            'discount_date' => $request->is_discount ? $request->discount_date : null,
            'show_in_navbar' => $request->show_in_navbar ?? 0,
        ]);
    
        $data->update($input);
    
        //-- Logic Section Ends
        return response()->json(__('Highlight Updated Successfully.'));
    }    

    //*** GET Request
    public function fastedit($id)
    {
        $data = Product::findOrFail($id);
        $sign = Currency::find(1);
        return view('admin.product.fastedit', compact('data', 'sign'));
    }

    //*** GET Request
    public function bulkedit()
    {
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::find(1);
        $storesList = Generalsetting::all();
        return view('admin.product.bulkedit', compact('cats', 'brands', 'storesList', 'sign'));
    }

    //*** POST Request
    public function fasteditsubmit(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'file' => 'mimes:zip'
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
        ];
    
        $validator = Validator::make($request->all(), $rules, $customs);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        $input = $this->withRequiredFields($request->all(), ['name']);
    
        //-- Logic Section
        $data = Product::findOrFail($id);
        $new_slug = Str::slug($data->name, '-') . '-' . strtolower(Str::slug($data->sku));
    
        // Update only if marketplace feature is enabled
        if (config("features.marketplace")) {
            $vendor_products = Product::where('slug', $data->slug)
                ->where('user_id', '!=', 0)
                ->get();
    
            foreach ($vendor_products as $v_prod) {
                // Update only changed fields
                $v_prod->fill($input); // Mass assignment for the updated fields
                $v_prod->slug = $new_slug; // Update slug separately
                $v_prod->update();
            }
        }
    
        // Only update the main product if there are changes
        $data->slug = $new_slug;
        $data->update($input);
        
        //-- Logic Section Ends
        //--- Redirect Section
        return response()->json(__('Product Updated Successfully.'));
        //--- Redirect Section Ends
    }    

    //*** POST Request
    public function bulkeditsubmit(Request $request)
    {
        if (!$request->array_id) {
            return response()->json(__("No products selected to update."));
        }
    
        $productIds = explode(',', $request->array_id);
        $input = array_filter($request->all());
        $input = $this->removeEmptyTranslations($input, null, true);
    
        // Carregar todos os produtos de uma vez para evitar múltiplas consultas
        $products = Product::findMany($productIds);
    
        $updatedProducts = [];
        
        foreach ($products as $data) {
            // Verifica se precisa aplicar a alteração de preço
            if ($request->change_price_type) {
                $input['price'] = $data->applyBulkEditChangePrice($request->change_price_type, $request->price);
            }
    
            // Verifica se houve alguma mudança nos dados
            if ($data->isDirty()) {
                $data->update($input);
                $updatedProducts[] = $data->id;
            }
        }
    
        // Se houverem produtos atualizados
        if (count($updatedProducts) > 0) {
            return response()->json(__('Products Updated Successfully.'));
        } else {
            return response()->json(__('No changes made to the selected products.'));
        }
    }
    
    //*** POST Request
    public function bulkdeletesubmit(Request $request)
    {
        $array_id = explode(',', $request['array_id']);
        foreach ($array_id as $prod_id) {
            $this->destroy($prod_id);
        }
        // --- Redirect Section
        $msg = __('Products Deleted Successfully.');
        return response()->json($msg);
    }

    //*** GET Request
    public function destroy($id)
    {
        // Carregar o produto com as relações necessárias
        $data = Product::with([
            'galleries', 'galleries360', 'reports', 'ratings', 
            'wishlists', 'clicks', 'comments.replies'
        ])->findOrFail($id);
    
        // Remover imagens e arquivos associados de uma vez
        $this->deleteAssociatedFiles($data);
    
        // Remover todos os registros relacionados de uma vez
        $this->deleteRelations($data);
    
        // Remover da loja
        $data->stores()->detach();
    
        // Deletar o produto
        $data->delete();
    
        return response()->json(__('Product Deleted Successfully.'));
    }
    
    private function deleteAssociatedFiles($data)
    {
        // Apagar arquivos associados ao produto
        $filesToDelete = [
            public_path('/storage/images/products/' . $data->photo),
            public_path('/storage/images/thumbnails/' . $data->thumbnail),
            public_path('/storage/files/' . $data->file),
        ];
    
        // Apagar imagens das galerias
        foreach ($data->galleries as $gal) {
            $filesToDelete[] = public_path('/storage/images/galleries/' . $gal->photo);
        }
    
        // Apagar imagens das galerias 360
        foreach ($data->galleries360 as $gal) {
            $filesToDelete[] = public_path('/storage/images/galleries360/' . $gal->photo);
        }
    
        // Excluir arquivos que existem
        foreach ($filesToDelete as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
    
    private function deleteRelations($data)
    {
        // Deletar registros em massa de cada relação
        $data->galleries()->delete();
        $data->galleries360()->delete();
        $data->reports()->delete();
        $data->ratings()->delete();
        $data->wishlists()->delete();
        $data->clicks()->delete();
        
        // Deletar comentários e respostas de uma vez
        foreach ($data->comments as $comment) {
            $comment->replies()->delete(); // Deletar as respostas
        }
        $data->comments()->delete(); // Deletar comentários
    }    

    public function getAttributes(Request $request)
    {
        // Mapeamento de tipos para modelos
        $modelMap = [
            'category' => 'App\Models\Category',
            'subcategory' => 'App\Models\Subcategory',
            'childcategory' => 'App\Models\Childcategory',
        ];
    
        // Obter o modelo correspondente ou retornar erro se não for válido
        $model = $modelMap[$request->type] ?? null;
        if (!$model) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
    
        // Obter atributos com suas opções de forma eficiente
        $attributes = Attribute::with('options')
            ->where('attributable_id', $request->id)
            ->where('attributable_type', $model)
            ->get();
    
        // Mapear atributos e opções
        $attrOptions = $attributes->map(function ($attribute) {
            return [
                'attribute' => $attribute,
                'options' => $attribute->options->map(function ($opt) {
                    return ['name' => $opt->name];
                }),
            ];
        });
    
        return response()->json($attrOptions);
    }    

    public function deleteProductImage(Request $request)
    {
        $data = Product::findOrFail($request->id);
    
        // Caminho para as imagens
        $imagePath = public_path() . '/storage/images/products/';
        $thumbnailPath = public_path() . '/storage/images/thumbnails/';
    
        // Deletar a foto do produto se não for URL e existir
        if ($data->photo && !filter_var($data->photo, FILTER_VALIDATE_URL) && file_exists($imagePath . $data->photo)) {
            unlink($imagePath . $data->photo);
        }
    
        // Deletar a thumbnail se existir
        if ($data->thumbnail && file_exists($thumbnailPath . $data->thumbnail)) {
            unlink($thumbnailPath . $data->thumbnail);
        }
    
        // Retorno da resposta
        return response()->json([
            'status' => true,
            'message' => __('Image Deleted Successfully')
        ]);
    }    

    public function generateThumbnailsFtp()
    {
        // Verifique se a integração FTP está habilitada uma vez
        $ftpFolder = resolve('storeSettings')->ftp_folder;
    
        if (!$ftpFolder) {
            return response()->json([
                'status' => false,
                'message' => __("You can't update thumbnails since FTP Integration is disabled.")
            ]);
        }
    
        // Processar os produtos em lotes para evitar sobrecarregar a memória
        Product::byStore()->where('status', 1)->chunk(100, function ($prods) use ($ftpFolder) {
            foreach ($prods as $prod) {
                Helper::generateProductThumbnailsFtp($ftpFolder, $prod->ref_code_int);
            }
        });
    
        // Mensagem de sucesso
        return response()->json([
            'status' => true,
            'message' => __('Thumbnails successfully updated!')
        ]);
    }    

    public function generateThumbnails()
    {
        $updated = 0;
        $thumb_path_base = public_path('storage/images/thumbnails/');
        $no_image = asset("assets/images/noimage.png");
    
        // Processa os produtos em blocos para evitar sobrecarga de memória
        Product::whereRaw('status = 1 and photo is not null')
            ->chunk(100, function ($products) use ($thumb_path_base, $no_image, &$updated) {
                foreach ($products as $product) {
                    // Verifica se o produto precisa de thumbnail
                    if ($product->thumbnail == $no_image && $product->photo != $no_image) {
                        $product->thumbnail = $product->photo;
    
                        // Atualiza o banco de dados de uma vez
                        $product->update(['thumbnail' => $product->thumbnail]);
    
                        // Prepara caminho da imagem para thumbnail
                        $img_dir = public_path('storage/images/products/' . $product->photo);
                        $thumb_path = $thumb_path_base . $product->photo;
    
                        // Verifica se a imagem existe
                        if (file_exists($img_dir)) {
                            $img = Image::make($img_dir);
                            $img->resize(null, 285, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($thumb_path);
                            $updated++;
                        }
                    }
                }
            });
    
        // Retorna resposta com base na quantidade de atualizações
        if ($updated > 0) {
            $msg = $updated . " " . __('Thumbnails successfully updated!');
            $alert = false;
        } else {
            $msg = __('There is no thumbnails to update!');
            $alert = true;
        }
    
        return response()->json(['status' => true, 'message' => $msg, 'alert' => $alert]);
    }    
}
