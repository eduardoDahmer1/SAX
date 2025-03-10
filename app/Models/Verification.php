<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends LocalizedModel
{
    protected $fillable = ['user_id', 'attachments', 'admin_warning', 'status'];
    protected $with = ['translations'];
    public $translatedAttributes = ['text', 'warning_reason'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => __('Deleted'),
            'email' => __('Deleted'),
        ]);
    }
}
