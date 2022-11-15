<?php


namespace App\Helpers;

class Singleton
{
    public static function instance()
    {
        return new Singleton;
    }
}
