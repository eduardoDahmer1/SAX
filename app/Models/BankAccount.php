<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BankAccount extends CachedModel
{
    use LogsActivity;

    protected $fillable = [
        'name', 'info', 'status'
    ];

    public $timestamps = false;

    // Definindo o que realmente precisa ser logado para evitar sobrecarga de dados
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('bank_accounts')
            ->logOnly(['name', 'status']) // Apenas os campos realmente necessários
            ->logOnlyDirty();
    }

    // Cache para evitar consultas repetidas
    public static function getById($id)
    {
        return Cache::remember("bank_account_{$id}", now()->addMinutes(60), function () use ($id) {
            return self::find($id);
        });
    }
}
