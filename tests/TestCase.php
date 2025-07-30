<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Otnansirk\Dana\DanaCoreServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DanaCoreServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup DANA configuration for testing
        $app['config']->set('dana.env', 'development');
        $app['config']->set('dana.active', false);
        $app['config']->set('dana.is_production', false);
        $app['config']->set('dana.api_url', 'https://api-sandbox.saas.dana.id');
        $app['config']->set('dana.web_url', 'https://m.sandbox.dana.id');
        $app['config']->set('dana.merchant_id', 'test_merchant_id');
        $app['config']->set('dana.client_id', 'test_client_id');
        $app['config']->set('dana.client_secret', 'test_client_secret');
        $app['config']->set('dana.version', '2.0');
        $app['config']->set('dana.date_format', 'Y-m-d\TH:i:sP');
        $app['config']->set('dana.expired_after', 60);
        $app['config']->set('dana.order_notify_url', '');
        $app['config']->set('dana.pay_return_url', '');
        $app['config']->set('dana.ssh_public_key', 'test_public_key');
        $app['config']->set('dana.ssh_private_key', 'test_private_key');
        $app['config']->set('dana.fee_tax', 0.11);
        $app['config']->set('dana.mdr_percent.credit_card', 0.018);
        $app['config']->set('dana.mdr_percent.debit_card', 0.018);
        $app['config']->set('dana.mdr_percent.balance', 0.012);
        $app['config']->set('dana.mdr_percent.direct_debit_credit_card', 0.012);
        $app['config']->set('dana.mdr_percent.direct_debit_debit_card', 0.012);
        $app['config']->set('dana.mdr_percent.online_credit', 0.012);
        $app['config']->set('dana.mdr_before_tax.virtual_account', 2000);
        $app['config']->set('dana.oauth_scopes', 'CASHIER,QUERY_BALANCE,DEFAULT_BASIC_PROFILE,MINI_DANA');
        $app['config']->set('dana.user_resources', ['BALANCE', 'TRANSACTION_URL', 'MASK_DANA_ID', 'TOPUP_URL', 'OTT']);
    }
}
