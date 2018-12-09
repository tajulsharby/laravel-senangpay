<?php

namespace Jomos\SenangPay;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/senang-pay.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('senang-pay.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'senang-pay'
        );

        $this->app->bind('senang-pay', function () {
            return new SenangPay();
        });
    }
}
