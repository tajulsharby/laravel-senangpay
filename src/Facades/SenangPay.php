<?php

namespace Jomos\SenangPay\Facades;

use Illuminate\Support\Facades\Facade;

class SenangPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'senang-pay';
    }
}
