<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

use AvtoDev\MonetaApi\Tests\Traits\Mock\TraitMock;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

/**
 * Class FormatPhoneTraitTest.
 *
 * @group traits
 */
class FormatPhoneTraitTest extends AbstractTraitTestCase
{
    protected $arrayOfPhones = [
        '+7 1234567891',
        81234567891,
        '8 (1234) 567-891',
        '812345\67891',
    ];

    protected function setUp()
    {
        parent::setUp();
        $mock        = new TraitMock;
        $this->class = \Mockery::mock($mock)->shouldAllowMockingProtectedMethods()->makePartial();
    }

    public function testNormalize()
    {
        foreach ($this->arrayOfPhones as $phone) {
            $this->assertEquals('81234567891', $number = $this->class->normalizePhone($phone));
            $this->assertTrue(is_int($number));
        }
    }

    public function testFormat()
    {
        foreach ($this->arrayOfPhones as $phone) {
            $this->assertEquals('8 (123) 456-78-91', $this->class->formatPhone($phone));
        }
    }

    public function testException()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->class->formatPhone(1234567);
    }
}
