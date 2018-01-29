<?php

namespace Rentberry\Coinmarketcap\Tests;

use PHPUnit\Framework\TestCase;
use Rentberry\Coinmarketcap\Coinmarketcap;
use Rentberry\Coinmarketcap\Exception;

class CoinmarketcapTest extends TestCase
{
    /**
     * @var Coinmarketcap
     */
    private $client;

    public function setUp()
    {
        $this->client = new Coinmarketcap();
    }

    public function testGetTicker()
    {
        $ticker = $this->client->getTicker('bitcoin');
        $this->assertArrayHasKey('id', $ticker);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionCode 404
     */
    public function testGetInvalidTicker()
    {
        $this->client->getTicker('invalid_id');
    }

    public function testGetTickers()
    {
        $tickers = $this->client->getTickers(10, 10);
        $this->assertCount(10, $tickers);
    }

    public function testGetGlobal()
    {
        $data = $this->client->getGlobal();
        $this->assertArrayHasKey('last_updated', $data);
    }

    public function testGetExchangeRate()
    {
        $rate = $this->client->getExchangeRate('ethereum', 'USD');
        $this->assertInternalType('string', $rate);
    }

    public function testConvertToCrypto()
    {
        $mock = $this->getMockBuilder(Coinmarketcap::class)->setMethods(['getTicker'])->getMock();
        $mock->method('getTicker')
            ->willReturn(['price_usd' => '1000.10']);

        $amount = $mock->convertToCrypto(10, 'USD', 'ethereum');
        $this->assertEquals('0.009999000099990000', $amount);
    }

    public function testConvertToFiat()
    {
        $mock = $this->getMockBuilder(Coinmarketcap::class)->setMethods(['getTicker'])->getMock();
        $mock->method('getTicker')
            ->willReturn(['price_usd' => '1000.10']);

        $amount = $mock->convertToFiat(10, 'ethereum', 'USD');
        $this->assertEquals('10001.00', $amount);
    }
}
