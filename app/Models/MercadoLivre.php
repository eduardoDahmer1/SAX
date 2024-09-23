<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MercadoLivre extends CachedModel
{
    protected $table = 'mercadolivre';
    protected $fillable = ['app_id', 'client_secret', 'access_token', 'refresh_token'];
    public $timestamps = true;
    private $gallery_array = [];
    private $shipping_array = [];

    public function curlPost($url, $headers = [], $data = []): array
    {
        return $this->curlRequest($url, $headers, $data, 'POST');
    }

    public function curlPut($url, $headers = [], $data = []): array
    {
        return $this->curlRequest($url, $headers, $data, 'PUT');
    }
    public function curlGet($url, $headers = []): array
    {
        return $this->curlRequest($url, $headers, [], 'GET');
    }
    private function curlRequest($url, $headers, $data, $method): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_CUSTOMREQUEST => $method,
        ]);
        $resp = curl_exec($curl);
        return (array) json_decode($resp);
    }

    public function productBusinessLogic(Product $product, $category_brand)
    {
        $condition = $product->product_condition == 1 ? "used" : "new";
        $name = env("APP_ENV") == "production" ? $product->mercadolivre_name : $product->mercadolivre_name . " - No Ofertar";

        $attributes = $this->getProductAttributes($product);
        if (!$attributes) {
            return redirect()->route('admin-prod-index')->with('error', __("Nenhum atributo foi cadastrado. Acesse Ações -> Editar -> Mercado Livre e cadastre os Atributos para melhorar seu anúncio."));
        }

        return [
            "title" => $name,
            "category_id" => $category_brand['category_id'],
            "price" => $product->mercadolivre_price ?? $product->price,
            "currency_id" => "BRL",
            "available_quantity" => $product->stock,
            "buying_mode" => "buy_it_now",
            "condition" => $condition,
            "listing_type_id" => $product->mercadolivre_listing_type_id,
            "sale_terms" => $this->getSaleTerms($product),
            "pictures" => $this->getPicturesArray($product),
            "shipping" => $this->getShippingData(),
            "attributes" => $attributes,
        ];
    }

    public function productUpdateBusinessLogic(Product $product, $category_brand)
    {
        $name = env("APP_ENV") == "production" ? $product->mercadolivre_name : $product->mercadolivre_name . " - No Ofertar";

        $attributes = $this->getProductAttributes($product);
        if (!$attributes) {
            return redirect()->route('admin-prod-index')->with('error', __("Nenhum atributo foi cadastrado. Acesse Ações -> Editar -> Mercado Livre e cadastre os Atributos para melhorar seu anúncio."));
        }
        return [
            "title" => $name,
            "price" => $product->mercadolivre_price ?? $product->price,
            "currency_id" => "BRL",
            "available_quantity" => $product->stock,
            "sale_terms" => $this->getSaleTerms($product),
            "pictures" => $this->getPicturesArray($product),
            "shipping" => $this->getShippingData(),
            "attributes" => $attributes,
        ];
    }

    private function getSaleTerms(Product $product): array
    {
        $saleTerms = [
            [
                "id" => "WARRANTY_TYPE",
                "name" => "Tipo de Garantia",
                "value_id" => $product->mercadolivre_warranty_type_id,
                "value_name" => $product->mercadolivre_warranty_type_name,
                "values" => [[
                    "id" => $product->mercadolivre_warranty_type_id,
                    "name" => $product->mercadolivre_warranty_type_name,
                ]],
            ],
            [
                "id" => "WARRANTY_TIME",
                "value_name" => $product->mercadolivre_warranty_time . ' ' . $product->mercadolivre_warranty_time_unit,
            ],
        ];

        if ($product->mercadolivre_without_warranty) {
            array_pop($saleTerms);
        }

        return $saleTerms;
    }
    public function getProductAttributes(Product $product)
    {
        $attrs = json_decode($product->mercadolivre_category_attributes, true);
        return $attrs ? array_filter(array_map(function ($id, $attr) {
            return !is_null($attr['value']) ? ['id' => $id, 'value_name' => $attr['value']] : null;
        }, array_keys($attrs), $attrs)) : false;
    }

    public function getShippingData(): array
    {
        return [
            "mode" => "custom",
            "local_pick_up" => Pickup::count() > 0,
            "free_shipping" => false,
            "costs" => $this->getPaidShippings(),
        ];
    }

    public function getPaidShippings(): array
    {
        return Shipping::where('status', 1)->where('price', '>', 0)->get()->map(function ($shipping) {
            return [
                'description' => $shipping->title,
                'cost' => $shipping->price,
            ];
        })->toArray();
    }
    public function getCategoryId($name)
    {
        if (!$name) return null;
        $meli = self::first();
        $url = config('mercadolivre.api_base_url') . "sites/MLB/domain_discovery/search?limit=1&q=" . urlencode($name);
        $headers = ["Authorization: Bearer " . $meli->access_token];
        return $this->curlGet($url, $headers)[0]->category_id;
    }
    public function getCategoryAttributes($category_id)
    {
        $meli = self::first();
        $url = config('mercadolivre.api_base_url') . "categories/" . $category_id . "/attributes";
        $headers = ["Authorization: Bearer " . $meli->access_token];
        $resp = $this->curlGet($url, $headers);

        if (isset($resp['status'])) {
            return;
        }
        return array_reduce($resp, function ($arr, $attribute) {
            $arr[$attribute->id] = [
                'name' => $attribute->name,
                'value' => null,
                'required' => isset($attribute->tags->required),
                'value_type' => $attribute->value_type,
                'values' => $attribute->values ?? null,
                'allowed_units' => $attribute->allowed_units ?? null,
                'value_max_length' => $attribute->value_max_length ?? null,
                'tooltip' => $attribute->tooltip ?? null,
                'hint' => $attribute->hint ?? null,
            ];
            return $arr;
        }, []);
    }
    public function getListingTypes()
    {
        $meli = self::first();
        $url = config('mercadolivre.api_base_url') . "sites/MLB/listing_types";
        $headers = ["Authorization: Bearer " . $meli->access_token];
        return $this->curlGet($url, $headers);
    }
    public function getListingTypeDetail($listingTypeId)
    {
        $meli = self::first();
        $url = config('mercadolivre.api_base_url') . "sites/MLB/listing_types/" . $listingTypeId;
        $headers = ["Authorization: Bearer " . $meli->access_token];
        return $this->curlGet($url, $headers);
    }
}
