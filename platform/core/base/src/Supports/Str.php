<?php


namespace FXC\Base\Supports;


class Str extends \Illuminate\Support\Str
{
    public static function replaceAll($search, $replace, &$subject)
    {
        foreach ($search as $item) {
            $subject = Str::replaceFirst($item, $replace, $subject);
        }
        return $subject;
    }

    public static function turkishReplacement($value)
    {
        $value = Str::replaceAll(['Ğ', 'ğ'], 'g', $value);
        $value = Str::replaceAll(['Ü', 'ü'], 'u', $value);
        $value = Str::replaceAll(['Ş', 'ş'], 's', $value);
        $value = Str::replaceAll(['Ç', 'ç'], 'c', $value);
        $value = Str::replaceAll(['Ö', 'ö'], 'o', $value);
        $value = Str::replaceAll(['I', 'İ', 'ı'], 'i', $value);

        return $value;
    }

    public static function isURL($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
}
