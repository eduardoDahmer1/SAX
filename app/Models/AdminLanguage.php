<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Cache;

class AdminLanguage extends CachedModel
{
    use LogsActivity;

    public $timestamps = false;

    // Otimização: Desabilitar o log de atividades em leitura
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;

    // Cache para melhorar a performance de leituras repetitivas
    public static function getLanguagesWithCache()
    {
        return Cache::remember('admin_languages_all', now()->addMinutes(60), function () {
            // Cache de 60 minutos para todas as línguas
            return self::all();
        });
    }

    // Logs de atividades
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('admin_languages')
            ->logFillable()  // loga apenas os campos 'fillable' alterados
            ->logOnlyDirty();  // loga apenas os campos modificados
    }
}
