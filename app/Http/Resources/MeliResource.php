<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class MeliResource extends JsonResource
{

    public function toArray($request)
    {
        return $request;
    }
}
