<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends CachedModel
{
    protected function withDefaultDeleted()
    {
        return function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        };
    }
    public function order()
    {
        return $this->belongsTo(Order::class)->withDefault($this->withDefaultDeleted());
    }
    public function receipt()
    {
        return $this->belongsTo(Order::class, 'receipt')->withDefault($this->withDefaultDeleted());
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault($this->withDefaultDeleted());
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id')->withDefault($this->withDefaultDeleted());
    }
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault($this->withDefaultDeleted());
    }
    public function conversation()
    {
        return $this->belongsTo(AdminUserConversation::class)->withDefault($this->withDefaultDeleted());
    }
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
    public function scopeNotRead($query)
    {
        return $query->where('is_read', false);
    }
    public static function countNotRead()
    {
        return self::notRead()->orderBy('id', 'DESC')->count();
    }
}
