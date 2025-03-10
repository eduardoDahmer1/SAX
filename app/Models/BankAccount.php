<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BankAccount extends CachedModel
{
    use LogsActivity;

    protected $fillable = ['name', 'info', 'status'];
    public $timestamps = false;

    /**
     * Configuração para log de atividade.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('bank_accounts')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Escopo para filtrar contas bancárias ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Habilita o cache de consultas para evitar acessos desnecessários ao banco.
     */
    protected static function booted()
    {
        static::addGlobalScope('defaultOrder', function ($query) {
            $query->orderBy('name');
        });
    }
}
