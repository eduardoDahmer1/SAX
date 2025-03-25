<?php

namespace App\Models;
use App\Enums\AssociationType;
use stdClass;
use App\Models\Currency;
use App\Models\Generalsetting;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends LocalizedModel
{
    use LogsActivity;
    protected $storeSettings;
    protected $with = ['translations'];

    public $translatedAttributes = [
        'name',
        'details',
        'ship',
        'policy',
        'meta_tag',
        'meta_description',
        'features',
        'tags',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'product_type',
        'affiliate_link',
        'sku',
        'gtin',
        'brand_id',
        'subcategory_id',
        'childcategory_id',
        'attributes',
        'photo',
        'size',
        'size_qty',
        'size_price',
        'product_size',
        'color',
        'price',
        'previous_price',
        'stock',
        'status',
        'views',
        'featured',
        'best',
        'top',
        'hot',
        'show_in_navbar',
        'latest',
        'big',
        'trending',
        'sale',
        'colors',
        'product_condition',
        'youtube',
        'type',
        'file',
        'license',
        'license_qty',
        'link',
        'platform',
        'region',
        'licence_type',
        'measure',
        'discount_date',
        'is_discount',
        'whole_sell_qty',
        'whole_sell_discount',
        'catalog_id',
        'slug',
        'ref_code',
        'ref_code_int',
        'mpn',
        'free_shipping',
        'max_quantity',
        'weight',
        'width',
        'height',
        'length',
        'external_name',
        'color_qty',
        'color_price',
        'being_sold',
        'vendor_min_price',
        'vendor_max_price',
        'color_gallery',
        'material',
        'material_gallery',
        'material_qty',
        'material_price',
        'show_price',
        'mercadolivre_category_attributes',
        'mercadolivre_name',
        'mercadolivre_description',
        'mercadolivre_id',
        'mercadolivre_listing_type_id',
        'mercadolivre_price',
        'mercadolivre_warranty_type_id',
        'mercadolivre_warranty_type_name',
        'mercadolivre_warranty_time',
        'mercadolivre_warranty_time_unit',
        'mercadolivre_without_warranty',
        'promotion_price',
    ];

    public function __construct()
    {
        $this->storeSettings = resolve('storeSettings');

        parent::__construct();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('products')
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['views'])
            ->dontSubmitEmptyLogs();
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public static function filterProducts($collection)
    {
        $maxPrice = $_GET['max'] ?? null;
    
        // Carrega os relacionamentos necessários para evitar múltiplas consultas ao banco
        if ($collection->isNotEmpty()) {
            $collection->load('user');
        }
    
        return $collection->filter(function ($data) use ($maxPrice) {
            if ($data->user_id != 0 && optional($data->user)->is_vendor != 2) {
                return false;
            }
    
            $vendorPrice = $data->vendorSizePrice();
    
            if ($maxPrice !== null && $vendorPrice >= $maxPrice) {
                return false;
            }
    
            $data->price = $vendorPrice;
    
            return true;
        });
    }    

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'name' => __('Deleted')
        ]);
    }
    
    public function associatedProductsByColor()
    {
        return $this->associatedProducts()->wherePivot('association_type', AssociationType::Color);
    }

    public function associatedProductsBySize()
    {
        return $this->associatedProducts()->wherePivot('association_type', AssociationType::Size);
    }

    public function associatedProductsByLook()
    {
        return $this->associatedProducts()->wherePivot('association_type', AssociationType::Look);
    }
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => __('Deleted')
        ]);
    }    

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    } 

    // public function subcategory()
    // {
    //     return $this->belongsTo(Subcategory::class)->withDefault([
    //         'name' => __('Deleted')
    //     ]);
    // }   

    public function childcategory()
    {
        return $this->belongsTo(Childcategory::class)->withDefault([
            'name' => __('Deleted')
        ]);
    }    

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery');
    }
    public function pickups()
    {
        return $this->belongsToMany(Pickup::class);
    }
    public function galleries360()
    {
        return $this->hasMany('App\Models\Gallery360');
    }
    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }
    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
    public function clicks()
    {
        return $this->hasMany('App\Models\ProductClick');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => __('Deleted')
        ]);
    }    
    public function associatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'associated_products', 'product_id', 'associated_product_id');
    }
    public function fatherProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'associated_products', 'associated_product_id', 'product_id');
    }
    public function reports()
    {
        return $this->hasMany('App\Models\Report', 'user_id');
    }
    public function stores()
    {
        return $this->belongsToMany('App\Models\Generalsetting', 'product_store', 'product_id', 'store_id');
    }
    public function scopeByStore($query)
    {
        $storeId = $this->storeSettings->id;
    
        return $query->whereHas('stores', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        });
    }    
    public function scopeIsActive($query)
    {
        return $query->where('products.status', 1);
    }
    public function scopeIsBeingSold($query)
    {
        return $query->where('products.being_sold', 1);
    }
    public function vendorPrice()
    {
        $price = $this->price;
        $storeSettings = $this->storeSettings;
    
        if ($this->user_id != 0) {
            $price += $storeSettings->fixed_commission + ($price / 100) * $storeSettings->percentage_commission;
        }
    
        return $price + ($price * ($storeSettings->product_percent / 100));
    }    
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isValidUrl($this->photo) 
                ? $this->photo 
                : asset('storage/images/products/' . $this->photo)
        );
    }
    
    protected function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }    
    public function vendorSizePrice()
    {
        $price = $this->price;
        $storeSettings = $this->storeSettings;
    
        if ($this->user_id != 0) {
            $price += $storeSettings->fixed_commission + ($price / 100) * $storeSettings->percentage_commission;
        }
    
        // Size Section
        if (!empty($this->size)) {
            foreach ($this->size as $key => $size) {
                if ($this->size_qty[$key] > 0) {
                    $price += $this->size_price[$key];
                    break; // Only add the first available size
                }
            }
        }
    
        // Color Section
        if (!empty($this->color)) {
            foreach ($this->color as $key => $color) {
                if ($this->color_qty[$key] > 0) {
                    $price += $this->color_price[$key];
                    break; // Only add the first available color
                }
            }
        }
    
        // Attribute Section
        if (!empty($this->attributes["attributes"])) {
            $attrArr = json_decode($this->attributes["attributes"], true);
            foreach ($attrArr as $attrVal) {
                if (isset($attrVal['details_status']) && $attrVal['details_status'] == 1 && !empty($attrVal['values'])) {
                    $price += $attrVal['prices'][0]; // Add the first price of the attribute
                    break;
                }
            }
        }
    
        // Add product percentage commission
        return $price * (1 + ($storeSettings->product_percent / 100));
    }    
    public function setCurrency()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }
    
        $price = $this->price;
        $currencyId = Session::get('currency', $this->storeSettings->currency_id);
        
        // Carregar a moeda apenas uma vez
        $curr = Currency::find($currencyId);
    
        // Se a moeda não for encontrada, retornar preço original
        if (!$curr) {
            return '';
        }
    
        // Calcular o preço com a moeda
        $price = round($price * $curr->value, 2);
    
        // Adicionar a porcentagem do produto no preço
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Formatar o preço conforme as configurações de moeda
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retornar preço com o símbolo da moeda na posição correta
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    
    public function setCurrencyFirst()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price || $this->storeSettings->currency_id == 1) {
            return '';
        }
    
        // Buscar a moeda apenas uma vez
        $curr = Currency::find(1);
    
        // Se a moeda não for encontrada, retornar preço original
        if (!$curr) {
            return '';
        }
    
        // Calcular o preço com a moeda
        $price = round($this->price * $curr->value, 2);
    
        // Adicionar a porcentagem do produto no preço
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Formatar o preço conforme as configurações de moeda
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retornar preço com o símbolo da moeda na posição correta
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public function firstCurrencyPrice()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }
    
        // Inicializar preço base
        $price = $this->price;
    
        // Calcular preço com comissão do vendedor, se necessário
        if ($this->user_id != 0) {
            $price += $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }
    
        // Ajustar preço com base no tamanho, cor e material
        $price += !empty($this->size) && isset($this->size_price[0]) ? $this->size_price[0] : 0;
        $price += !empty($this->color) && isset($this->color_price[0]) ? $this->color_price[0] : 0;
        $price += !empty($this->material) && isset($this->material_price[0]) ? $this->material_price[0] : 0;
    
        // Ajustar preço com atributos, se necessário
        if (!empty($this->attributes["attributes"])) {
            $attrArr = json_decode($this->attributes["attributes"], true);
            foreach ($attrArr as $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    $price += $attrVal['prices'][0] ?? 0;  // Atribui o primeiro preço válido
                    break; // Apenas o primeiro preço conta
                }
            }
        }
    
        // Buscar a moeda
        $curr = Currency::find(1); // Utilizando find() para otimizar a consulta ao banco
        if (!$curr) {
            return ''; // Se a moeda não for encontrada, retorna vazio
        }
    
        // Calcular preço final com a porcentagem do produto
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Converter o preço para a moeda desejada
        $price = round($price * $curr->value, 2);
    
        // Formatar preço
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retornar preço com o símbolo da moeda
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public function showVendorMinPrice()
    {
        $price = $this->vendor_min_price;
    
        // Calcular preço com comissão do vendedor, se necessário
        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }
    
        // Ajustar preço com base no tamanho
        $price += $this->getSizePrice();
    
        // Ajustar preço com base na cor
        $price += $this->getColorPrice();
    
        // Ajustar preço com base nos atributos
        $price += $this->getAttributePrice();
    
        // Buscar moeda
        $curr = $this->getCurrency();
    
        // Converter preço para a moeda desejada e aplicar porcentagem de produto
        $price = round($price * $curr->value, 2);
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Formatar preço
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retornar preço com o símbolo da moeda
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }
    
    /**
     * Retorna o preço baseado no tamanho
     */
    private function getSizePrice()
    {
        if (!empty($this->size)) {
            foreach ($this->size as $key => $size) {
                if ($this->size_qty[$key] > 0) {
                    return $this->size_price[$key];
                }
            }
        }
        return 0;
    }
    
    /**
     * Retorna o preço baseado na cor
     */
    private function getColorPrice()
    {
        if (!empty($this->color)) {
            foreach ($this->color as $key => $color) {
                if ($this->color_qty[$key] > 0) {
                    return $this->color_price[$key];
                }
            }
        }
        return 0;
    }
    
    /**
     * Retorna o preço adicional baseado nos atributos
     */
    private function getAttributePrice()
    {
        $price = 0;
        if (!empty($this->attributes["attributes"])) {
            $attrArr = json_decode($this->attributes["attributes"], true);
            foreach ($attrArr as $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    $price += $attrVal['prices'][0] ?? 0; // Apenas o primeiro preço
                    break; // Apenas o primeiro preço conta
                }
            }
        }
        return $price;
    }
    
    /**
     * Retorna a moeda a ser usada
     */
    private function getCurrency()
    {
        if (Session::has('currency') && $this->storeSettings->is_currency) {
            return Currency::find(Session::get('currency'));
        }
        return Currency::find($this->storeSettings->currency_id);
    }    

    public function showVendorMaxPrice()
    {
        // Define o preço base
        $price = $this->vendor_max_price;
    
        // Aplica comissão se o usuário não for admin
        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }
    
        // Aplica preços adicionais baseados em tamanho, cor e atributos
        foreach (['size', 'color'] as $type) {
            if (!empty($this->{$type})) {
                foreach ($this->{$type} as $key => $item) {
                    if ($this->{$type . '_qty'}[$key] > 0) {
                        $price += $this->{$type . '_price'}[$key];
                        break; // Apenas o primeiro preço conta
                    }
                }
            }
        }
    
        // Aplica preço dos atributos
        $attributes = $this->attributes["attributes"] ?? null;
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
            if (!empty($attrArr)) {
                foreach ($attrArr as $attrVal) {
                    if (!empty($attrVal['details_status']) && $attrVal['details_status'] == 1) {
                        foreach ($attrVal['values'] as $key => $value) {
                            $price += $attrVal['prices'][$key];
                            break; // Apenas o primeiro preço conta
                        }
                    }
                }
            }
        }
    
        // Obtém a moeda atual
        $curr = Session::has('currency') && $this->storeSettings->is_currency 
            ? Currency::find(Session::get('currency')) 
            : Currency::find($this->storeSettings->currency_id);
    
        // Aplica o valor da moeda e da porcentagem do produto
        $price = round($price * $curr->value, 2);
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Formata o preço final
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retorna o preço com o símbolo da moeda
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public function showVendorPrice()
    {
        // Define o preço base
        $price = $this->price;
    
        // Aplica comissão se o usuário não for admin
        if ($this->user_id != 0) {
            $price += $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }
    
        // Aplica preços adicionais baseados em tamanho, cor e atributos
        foreach (['size', 'color'] as $type) {
            if (!empty($this->{$type})) {
                foreach ($this->{$type} as $key => $item) {
                    if ($this->{$type . '_qty'}[$key] > 0) {
                        $price += $this->{$type . '_price'}[$key];
                        break; // Apenas o primeiro preço conta
                    }
                }
            }
        }
    
        // Aplica preço dos atributos
        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
            if (!empty($attrArr)) {
                foreach ($attrArr as $attrVal) {
                    if (isset($attrVal['details_status']) && $attrVal['details_status'] == 1) {
                        foreach ($attrVal['values'] as $key => $value) {
                            $price += $attrVal['prices'][$key];
                            break; // Apenas o primeiro preço conta
                        }
                    }
                }
            }
        }
    
        // Obtém a moeda atual
        $curr = Session::has('currency') && $this->storeSettings->is_currency 
            ? Currency::find(Session::get('currency')) 
            : Currency::find($this->storeSettings->currency_id);
    
        // Aplica o valor da moeda e da porcentagem do produto
        $price = round($price * $curr->value, 2);
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Formata o preço final
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retorna o preço com o símbolo da moeda
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public function showPrice()
    {
        // Verifica se os preços devem ser exibidos
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }
    
        // Define o preço inicial baseado na promoção, se houver
        if ($this->price > $this->promotion_price && $this->promotion_price > 0) {
            $price = $this->promotion_price;
        } else {
            $price = $this->price;
        }
    
        // Aplica comissão se o usuário não for admin
        if ($this->user_id != 0) {
            $price += $this->storeSettings->fixed_commission + ($price / 100) * $this->storeSettings->percentage_commission;
        }
    
        // Aplica preço adicional baseado em tamanho, cor e material
        foreach (['size', 'color', 'material'] as $type) {
            if (!empty($this->{$type})) {
                foreach ($this->{$type} as $key => $item) {
                    if ($this->{$type . '_qty'}[$key] > 0) {
                        $price += $this->{$type . '_price'}[$key];
                        break;
                    }
                }
            }
        }
    
        // Aplica o preço dos atributos
        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
            if (!empty($attrArr)) {
                foreach ($attrArr as $attrVal) {
                    if (isset($attrVal['details_status']) && $attrVal['details_status'] == 1) {
                        foreach ($attrVal['values'] as $key => $value) {
                            $price += $attrVal['prices'][$key];
                            break; // Apenas o primeiro preço conta
                        }
                    }
                }
            }
        }
    
        // Obtém a moeda atual
        $curr = Session::has('currency') && $this->storeSettings->is_currency 
            ? Currency::find(Session::get('currency')) 
            : Currency::find($this->storeSettings->currency_id);
    
        // Aplica o valor da moeda e da porcentagem do produto
        $price = round($price * $curr->value, 2);
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Formata o preço final
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retorna o preço com o símbolo da moeda
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public function showPreviousPrice()
    {
        // Verifica se os preços devem ser exibidos
        if (!$this->storeSettings->show_product_prices || !$this->show_price || !$this->previous_price) {
            return '';
        }
    
        // Calcula o preço com comissão do vendedor, se necessário
        $price = $this->previous_price;
        if ($this->user_id != 0) {
            $price += $this->storeSettings->fixed_commission + ($price / 100) * $this->storeSettings->percentage_commission;
        }
    
        // Obtém a moeda correta
        $curr = Session::has('currency') && $this->storeSettings->is_currency 
            ? Currency::find(Session::get('currency')) 
            : Currency::find($this->storeSettings->currency_id);
    
        // Converte o preço para a moeda e aplica a porcentagem do produto
        $price = round($price * $curr->value, 2);
        $price += $price * ($this->storeSettings->product_percent / 100);
    
        // Formata o preço
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retorna o preço com o símbolo da moeda
        return $this->storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public static function signfirstPrice($price)
    {
        $storeSettings = resolve('storeSettings');
    
        // Retorna string vazia se a moeda for a padrão
        if ($storeSettings->currency_id == 1) {
            return '';
        }
    
        // Obtém a moeda padrão
        $curr = Currency::find(1);
    
        // Formata o preço
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
    
        // Retorna o preço com o símbolo da moeda no formato correto
        return $storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public static function convertPriceReverse($price)
    {
        $storeSettings = resolve('storeSettings');
    
        // Obtém a moeda atual ou a padrão
        $currencyId = Session::has('currency') && $storeSettings->is_currency 
            ? Session::get('currency') 
            : $storeSettings->currency_id;
    
        $curr = Currency::find($currencyId);
        $firstCurr = Currency::find(1); // Moeda principal
    
        // Converte o preço para a moeda base
        $price = round($price / $curr->value, 2);
    
        // Formata o preço
        $price = number_format($price, $firstCurr->decimal_digits, $firstCurr->decimal_separator, $firstCurr->thousands_separator);
    
        // Retorna o preço com o formato correto
        return $storeSettings->currency_format == 0 
            ? $firstCurr->sign . $price 
            : $price . $firstCurr->sign;
    }    

    public static function convertPrice($price)
    {
        $storeSettings = resolve('storeSettings');
    
        // Obtém a moeda atual ou a padrão
        $currencyId = Session::has('currency') && $storeSettings->is_currency 
            ? Session::get('currency') 
            : $storeSettings->currency_id;
    
        $curr = Currency::find($currencyId);
    
        // Converte e formata o preço
        $price = number_format(
            round($price * $curr->value, 2), 
            $curr->decimal_digits, 
            $curr->decimal_separator, 
            $curr->thousands_separator
        );
    
        // Retorna o preço no formato correto
        return $storeSettings->currency_format == 0 
            ? $curr->sign . $price 
            : $price . $curr->sign;
    }    

    public static function convertPriceDolar($price)
    {
        $storeSettings = resolve('storeSettings');
        if (Session::has('currency') && $storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($storeSettings->currency_id);
        }

        $valor_formatado = number_format($price, 2, '.', ',');
        
        if ($storeSettings->currency_format == 0) {
            return 'U$ ' . $valor_formatado;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function vendorConvertPrice($price)
    {
        $storeSettings = resolve('storeSettings');
        $curr = $curr = Currency::find($storeSettings->currency_id);
        $price = round($price * $curr->value, 2);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function convertPreviousPrice($price)
    {
        $storeSettings = resolve('storeSettings');

        $curr = $curr = Currency::find($storeSettings->currency_id);
        $price = round($price * $curr->value, 2);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showName()
    {
        $name = mb_strlen($this->name, 'utf-8') > 55 ? mb_substr($this->name, 0, 55, 'utf-8') . '...' : $this->name;
        return $name;
    }


    public function emptyStock()
    {
        return $this->withStock()->count() === 0;
    }

    public static function showTags()
    {
        $tags = Product::where('status', 1)->pluck('tags')->flatten()->filter()->unique()->values()->toArray();
        return $tags;
    }

    public function getSizeAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaxQuantityAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return $value;
    }

    public function getSizeQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizePriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorPriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaterialQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaterialPriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaterialAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getTagsAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getFeaturesAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorsAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getLicenseAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',,', $value);
    }

    public function getLicenseQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellDiscountAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getSlugAttribute($value)
    {
        return $value ? $value : 'noslugdefined';
    }

    public function getRefCodeIntAttribute($value)
    {
        return ($value == 0 ? $this->ref_code : $value);
    }

    public function getPhotoAttribute($value)
    {
        // Se for uma URL válida, retorna direto
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
    
        // Se não houver valor definido
        if (!$value) {
            if ($this->storeSettings->ftp_folder) {
                $ftpPath = public_path('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/');
                
                // Se o diretório existir, tenta encontrar uma imagem válida
                if (File::exists($ftpPath)) {
                    $extensions = ['.jpg', '.jpeg', '.gif', '.png'];
                    foreach (scandir($ftpPath) as $file) {
                        if (in_array(strtolower(strrchr($file, '.')), $extensions)) {
                            return asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/' . $file);
                        }
                    }
                }
            }
            return asset('assets/images/noimage.png');
        }
    
        // Verifica se a imagem existe na pasta de produtos
        if (File::exists(public_path('storage/images/products/' . $value))) {
            return $value;
        }
    
        // Se a imagem não existir e o usuário for admin, reseta o campo no banco
        if (Auth::guard('admin')->check()) {
            Product::where('id', $this->id)->update(['photo' => null]);
        }
    
        return asset('assets/images/noimage.png');
    }    

    public function getThumbnailAttribute($value)
    {
        // Se for uma URL válida, retorna diretamente
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
    
        // Se não houver valor definido
        if (!$value) {
            $ftpPath = public_path('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/');
            
            // Se o diretório FTP existir, busca uma imagem válida
            if (File::exists($ftpPath)) {
                $extensions = ['.jpg', '.jpeg', '.gif', '.png'];
                foreach (scandir($ftpPath) as $file) {
                    if (in_array(strtolower(strrchr($file, '.')), $extensions)) {
                        return asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/' . $file);
                    }
                }
            }
            return asset('assets/images/noimage.png');
        }
    
        // Verifica se a imagem existe na pasta de thumbnails
        if (File::exists(public_path('storage/images/thumbnails/' . $value))) {
            return $value;
        }
    
        // Se a imagem não existir e o usuário for admin, reseta o campo no banco
        if (Auth::guard('admin')->check()) {
            Product::where('id', $this->id)->update(['thumbnail' => null]);
        }
    
        return asset('assets/images/noimage.png');
    }
    

    public function getDiscountPercentAttribute($value)
    {
        if ($this->previous_price && $this->previous_price > $this->price) {
            $discountPercent = (($this->previous_price - $this->price) / $this->previous_price) * 100;
            return number_format($discountPercent, 0);
        }
        return null;
    }

    public static function scopeMercadoLivreProducts($query)
    {
        return $query->whereNotNull('mercadolivre_id');
    }

    /**
     * @param array $redplayData - Separated Redplay data
     * @return array|null $redplayArray - All-in-one Redplay data or null if invalid
     */
    public static function sanitizeRedplayData(array $redplayData)
    {
        if (!isset($redplayData['redplay_login'], $redplayData['redplay_password'], $redplayData['redplay_code'])) {
            return null;
        }

        $redPlayArray = [];

        foreach ($redplayData['redplay_login'] as $i => $login) {
            $redPlayArray[] = [
                'login'    => $login,
                'password' => $redplayData['redplay_password'][$i] ?? null,
                'code'     => $redplayData['redplay_code'][$i] ?? null
            ];
        }

        return $redPlayArray;
    }

    public function applyBulkEditChangePrice(string $action, $newPrice)
    {
        if (empty($newPrice)) {
            return $this->price;
        }
    
        $changePriceTypes = [
            "set_price"          => "setFixedPrice",
            "add_percentage"     => "addPricePercentage",
            "decrease_percentage"=> "decreasePricePercentage",
            "add_price"          => "addPriceValue",
            "decrease_price"     => "decreasePriceValue"
        ];
    
        return $changePriceTypes[$action] ?? null 
            ? $this->{$changePriceTypes[$action]}((float) $newPrice) 
            : $this->price;
    }    

    private function setFixedPrice(float $newPrice)
    {
        return $newPrice;
    }
    private function addPricePercentage(float $percentage)
    {
        return $this->price + (($percentage / 100) * $this->price);
    }
    private function decreasePricePercentage(float $percentage)
    {
        return $this->price - (($percentage / 100) * $this->price);
    }
    private function addPriceValue(float $newPrice)
    {
        return $this->price + $newPrice;
    }
    private function decreasePriceValue(float $newPrice)
    {
        return $this->price - $newPrice;
    }

    public function scopeWithStock($query)
    {
        return $query->whereRaw('(stock > 0 or stock is null)')->orWhereHas('associatedProducts', function (Builder $query) {
            $query->whereRaw('(stock > 0 or stock is null)')->where('associated_products.association_type', AssociationType::Size);
        });
    }

    public function is_available_to_buy()
    {
        return $this->storeSettings->is_cart_and_buy_available && $this->stock > 0;
    }

    public function scopeOnlyFatherProducts($query)
    {
        return $query->whereHas('associatedProducts', fn (Builder $q) => 
            $q->where('association_type', AssociationType::Size)
        )->orWhereDoesntHave('fatherProducts', fn (Builder $q) => 
            $q->where('association_type', AssociationType::Size)
        );
    }    
}
