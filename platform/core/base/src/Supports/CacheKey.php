<?php

namespace FXC\Base\Supports;

use Illuminate\Support\Str;

class CacheKey
{
    /**
     * @param  null  $user_id
     */
    static function get_user_id($user_id = null)
    {
        if (!$user_id) {
            $user_id = auth()->check() ? auth()->id() : null;
        }
        return $user_id;
    }

    /**
     * @param  null  $list_name
     */
    static function list_option_list($list_name): string
    {
        return "list_options:list:{$list_name}";
    }

    /**
     * @param  null  $user_id
     * @return string
     */
    public static function user_unread_notifications($user_id = null): string
    {
        $user_id = self::get_user_id($user_id);

        return "users:notifications:unread:{$user_id}";
    }

    /**
     * @param $table_name
     * @return string
     */
    public static function schema_table_columns($table_name): string
    {
        return "schema:tables:{$table_name}:columns";
    }

    /**
     * @return string
     */
    public static function schema_tables(): string
    {
        return "schema:tables";
    }

    /**
     * @param $table
     * @return string
     */
    public static function schema_table_columns_types($table): string
    {
        return "schema:tables:{$table}:columns_types";
    }

    /**
     * @param  string  $name
     * @return string
     */
    public static function all(string $name)
    {
        if ($name) {
            $name = Str::lower(Str::plural($name));
        }

        return "all:{$name}";
    }

    /**
     * @param  string  $name
     * @return string
     */
    public static function all_cached(string $name): string
    {
        if ($name) {
            $name = Str::lower(Str::plural($name));
        }

        return "all:cached:{$name}";
    }

    /**
     * @param  string  $name
     * @return string
     */
    public static function active_cached(string $name): string
    {
        if ($name) {
            $name = Str::lower(Str::plural($name));
        }

        return "active:cached:{$name}";
    }

    /**
     * @param  string  $pageName
     * @return string
     */
    public static function page(string $pageName): string
    {
        if ($pageName) {
            $pageName = Str::slug($pageName);
        }

        return "page:slug:{$pageName}";
    }

    /**
     * @param  string  $articleName
     * @return string
     */
    public static function article(string $articleName): string
    {
        if ($articleName) {
            $articleName = Str::slug($articleName);
        }

        return "article:slug:{$articleName}";
    }
}
