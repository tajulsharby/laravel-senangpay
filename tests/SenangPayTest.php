<?php

namespace Jomos\SenangPay\Tests;

use Jomos\SenangPay\Facades\SenangPay;
use Jomos\SenangPay\ServiceProvider;
use Orchestra\Testbench\TestCase;

class SenangPayTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'senang-pay' => SenangPay::class,
        ];
    }

    public function testExample()
    {
        assertEquals(1, 1);
    }
}
