<?php

namespace App\Providers;

use App\Models\Generalsetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class MailConfigServiceProvider extends ServiceProvider
{
    public function boot()
    {
        try {
            if (Schema::hasTable('generalsettings')) {
                $storeSettings = Generalsetting::where('is_default', 1)->first();

                if ($storeSettings) {
                    $mail_driver = $storeSettings->header_email === 'smtp' ? 'smtp' : 'sendmail';

                    $config = [
                        "driver" => $mail_driver,
                        "host" => $storeSettings->smtp_host ?? config('mail.host'),
                        "port" => $storeSettings->smtp_port ?? config('mail.port'),
                        "from" => [
                            "address" => $storeSettings->from_email ?? config('mail.from.address'),
                            "name" => $storeSettings->from_name ?? config('mail.from.name'),
                        ],
                        "encryption" => $storeSettings->email_encryption ?? config('mail.encryption'),
                        "username" => $storeSettings->smtp_user ?? config('mail.username'),
                        "password" => $storeSettings->smtp_pass ?? config('mail.password'),
                    ];

                    Config::set('mail', array_merge(Config::get('mail'), $config));
                }
            }
        } catch (Throwable $e) {
            return;
        }
    }

    public function register()
    {
    }
}
