<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BlogCategory extends LocalizedModel
{
    use LogsActivity;

    protected $fillable = ['slug'];
    public $timestamps = false;

    // Removendo 'with' para evitar carregamento antecipado de traduções desnecessárias
    // O carregamento pode ser feito de maneira condicional quando necessário
    public $translatedAttributes = ['name'];

    // Log apenas dos campos essenciais para reduzir a sobrecarga
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blog_categories')
            ->logOnly(['slug']) // Log apenas o campo 'slug', evitando log de dados desnecessários
            ->logOnlyDirty();
    }

    public function blogs()
    {
        return $this->hasMany('App\Models\Blog', 'category_id');
    }

    // Otimização no setter para o campo 'slug'
    public function setSlugAttribute($value)
    {
        // Utilizando str_slug do Laravel para otimizar a criação de slugs
        $this->attributes['slug'] = str_slug($value);
    }
}
