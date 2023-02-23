<?php


namespace App\Helpers;

class Singleton
{
    /**
     * @return Singleton
     */
    public static function instance(): Singleton
    {
        return new Singleton;
    }
}
