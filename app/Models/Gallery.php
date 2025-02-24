<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Gallery extends CachedModel
{
    protected $fillable = ['product_id', 'photo'];
    public $timestamps = false;

    /**
     * Método otimizado para obter a URL da foto.
     */
    public function getPhotoUrlAttribute()
    {
        // Checa se a foto é uma URL válida e se o arquivo não existe
        if ($this->isValidUrl($this->photo)) {
            return $this->photo;
        }

        // Usa o cache para evitar chamadas repetidas de file_exists
        return Cache::remember("gallery_photo_{$this->id}", now()->addMinutes(60), function () {
            // Verifica se o arquivo existe localmente
            return file_exists(public_path('storage/images/galleries/' . $this->photo)) 
                ? asset('storage/images/galleries/' . $this->photo) 
                : $this->photo;
        });
    }

    /**
     * Verifica se a string é uma URL válida.
     */
    private function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
