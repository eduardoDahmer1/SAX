<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class BrandResource extends JsonResource
{

    public function toArray($request)
    {
        $brand = [
            "id" => $this->id,
            "status" => $this->status,
            "name" => $this->name,
            "ref_code" => $this->ref_code
        ];
        return $brand;
    }
}
