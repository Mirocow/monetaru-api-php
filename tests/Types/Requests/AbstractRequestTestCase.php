<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Clients\MonetaApi;

abstract class AbstractRequestTestCase extends TestCase
{
    /**
     * @var MonetaApi
     */
    protected $api;

    protected $config = [];

    protected function setUp()
    {
        parent::setUp();
        $this->config = [
            'authorization' => [
                'username' => 'i',
                'password' => 'need',
            ],
            'accounts'      => [
                'fines'      => [
                    'id' => 'some',
                ],
                'commission' => [
                    'id' => 'body',
                ],
            ],
            'is_test'       => true,
        ];
        $this->api    = new MonetaApi($this->config);
    }
}
