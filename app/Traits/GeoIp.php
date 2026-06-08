<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
            $response = Http::withHeaders(['Accept' => 'application/json'])
                ->get(sprintf('http://ip-api.com/json/%s?fields=8445983', $this->ip()));

            $response->throw();

            $result = $response->body();
            Cache::put(str_replace('.', '_', $this->ip()), (object) (json_decode($result)), now()->addWeek());

            return (object) (json_decode($result));
        }

        return $value;
    }

    private function ip(): ?string
    {
        if (! app()->runningInConsole() && request()) {
            return request()->ip();
        }

        return null;
    }
}
