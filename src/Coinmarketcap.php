<?php declare(strict_types = 1);

namespace Rentberry\Coinmarketcap;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class Coinmarketcap
{
    /**
     * @var string
     */
    public $version;

    /**
     * Coinmarketcap constructor.
     * @param string $version
     * @param int $timeout
     */
    public function __construct($version = 'v1', int $timeout = 3)
    {
        $this->version = $version;
        $this->client = new Client([
            'base_uri' => \sprintf('https://api.coinmarketcap.com/%s/', $this->version),
            'timeout' => $timeout,
        ]);
    }

    /**
     * @param null $limit
     * @param null $start
     * @param null $convert
     * @return array
     * @throws \Rentberry\Coinmarketcap\Exception
     */
    public function getTickers($limit = null, $start = null, $convert = null): array
    {
        $req = new Request('GET', 'ticker');

        return $this->makeRequest($req, [
            'query' => [
                'limit' => $limit,
                'start' => $start,
                'convert' => $convert
            ]
        ]);
    }

    /**
     * @param string $id
     * @param null $convert
     * @return array
     * @throws \Rentberry\Coinmarketcap\Exception
     */
    public function getTicker(string $id, $convert = null): array
    {
        $req = new Request('GET', \sprintf('ticker/%s', $id));

        $data = $this->makeRequest($req, [
            'query' => [
                'convert' => $convert
            ]
        ]);

        return \array_shift($data);
    }

    /**
     * @param null $convert
     * @return array
     * @throws \Rentberry\Coinmarketcap\Exception
     */
    public function getGlobal($convert = null): array
    {
        $req = new Request('GET', 'global');

        return $this->makeRequest($req, [
            'query' => [
                'convert' => $convert
            ]
        ]);
    }

    /**
     * @param int|float|string $amount
     * @param string $from
     * @param string $id
     * @param int $scale
     * @return string
     * @throws \Rentberry\Coinmarketcap\Exception
     */
    public function convertToCrypto($amount, string $from, string $id, int $scale = 18): string
    {
        $rate = $this->getExchangeRate($id, $from);

        return \bcdiv((string) $amount, $rate, $scale);
    }

    /**
     * @param float $amount
     * @param string $id
     * @param string $to
     * @param int $scale
     * @return string
     * @throws \Rentberry\Coinmarketcap\Exception
     */
    public function convertToFiat($amount, string $id, string $to, int $scale = 4): string
    {
        $rate = $this->getExchangeRate($id, $to);

        return \bcmul((string) $amount, $rate, $scale);
    }

    /**
     * @param $id
     * @param $convert
     * @return mixed
     * @throws \Rentberry\Coinmarketcap\Exception
     */
    public function getExchangeRate($id, $convert): string
    {
        $ticker = $this->getTicker($id, $convert);

        $priceKey = \sprintf('price_%s', \strtolower($convert));

        if (!\array_key_exists($priceKey, $ticker) || $ticker[$priceKey] === 0) {
            throw new Exception('Invalid currency ticker');
        }

        return (string) $ticker[$priceKey];
    }

    /**
     * @param Request $request
     * @param array $options
     * @return array
     * @throws \Rentberry\Coinmarketcap\Exception
     */
    private function makeRequest(Request $request, array $options = []): array
    {
        try {
            $res = $this->client->send($request, $options);
        } catch (ClientException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $body = $res->getBody()->getContents();
        } catch (\RuntimeException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        return \json_decode($body, true);
    }
}
