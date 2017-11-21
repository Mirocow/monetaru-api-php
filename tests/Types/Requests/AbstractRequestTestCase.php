<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\MonetaApi;

abstract class AbstractRequestTestCase extends TestCase
{
    /**
     * @var MonetaApi
     */
    protected $api;

    protected $config = [
        'authorization' => [
            'username' => 'i',
            'password' => 'need',
        ],
        'accounts'      => [
            'fines_account'      => 'some',
            'commission_account' => 'body',
        ],
        'is_test'       => true,
    ];

    protected function setUp()
    {
        parent::setUp();
        $this->api = new MonetaApi($this->config);
    }
}
