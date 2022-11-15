<?php


namespace App\Traits;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

trait GeoIp
{
    /**
     * Handle an incoming request.
     * https://ip-api.com/docs/api:json
     * to get the fields=status,message,country,countryCode,region,regionName,city,currency,query
     * generated numeric
     * 8445983
     */
    public function ipInfo()
    {
        $value = Cache::get(str_replace('.', '_', $this->ip()));
        if (is_null($value)) {
            $client = new Client();
            $response = $client->request('GET', sprintf('http://ip-api.com/json/%s?fields=8445983', $this->ip()), [
                'headers' => ['Accept' => 'application/json'],
            ]);
            $result = $response->getBody()->getContents();
            Cache::put(str_replace('.', '_', $this->ip()), (object)(json_decode($result)), now()->addWeek());
            return ((object)(json_decode($result)));
        }
        return $value;
    }

    private function ip()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
}
