<?php


class Settings 
{
    public static function FileName()
    {
        return "settings";
    }
    public static function FilePath()
    {
        return __DIR__."/".self::FileName().".json";
    }
    public static function Save (array $array)
    {
        file_put_contents(self::FilePath(), json_encode($array));
    }
    public static function FileDefault()
    {
        $array = [
            "demending" => [
                "name"=>null,
                "pwd"=>null,
                "ApiKey"=>null,
            ],
            "chA" => null,
            "chB" => null,
            "app" => null,
            "country" => null,
            "com"=>null,
            "run"=>false,
            "countrys"=>[

            ],
        ];
        return $array;
    }
    public static function FileExists()
    {
        if (!file_exists(self::FilePath()))
        {
            self::Save(self::FileDefault());
        }
    }
    public static function Get ()
    {
        self::FileExists();
        return json_decode(file_get_contents(self::FilePath()), true);
    }
    
    /* 
        [phone]=>[
            [phone]=>phone number,
            [country]=>country,
            [time]=>time,
            [url_ban]=>url_ban,
            [url_code]=>url_code,
            [uto]=>uto,
        ]
    */
    
}

// Data::Show(true, "success", Data::List());