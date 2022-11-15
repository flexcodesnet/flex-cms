<?php

namespace App\Support;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    public function find($by, $default = null)
    {
        $result = null;
        foreach ($this as $key => $value) {
            if (isset($value->{$by}) && $value->{$by} == $default) {
                $result = $value;
            }
        }

        return $result;
    }

    public function paginate($perPage, $total = null, $pageName = 'page', $page = null)
    {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
        return new LengthAwarePaginator(
            $this->forPage($page, $perPage),
            $total ?: $this->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }
}
