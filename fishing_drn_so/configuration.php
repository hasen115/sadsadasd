<?php


class Configuration
{
    public static function token()
    {
        return "7169936053:AAEssOyt8YkY9tR2xdGTmcm3NagxwlYMhb8";
    }
    public static function admin ()
    {
        return 1831369371;
    } 
    public static function admins ()
    {
        return [
            self::admin(),
            1831369371,
        ];
    }
}
