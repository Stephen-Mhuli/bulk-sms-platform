<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $config = array(
            'driver' => 'smtp',
            'host' => get_settings('mail_host'),
            'port' => get_settings('mail_port'),
            'from' => array('address' => get_settings('mail_from'), 'name' => get_settings('mail_name')),
            'encryption' => get_settings('mail_encryption'),
            'username' => get_settings('mail_username'),
            'password' => get_settings('mail_password'),
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        );
        Config::set('mail', $config);
    }
}
