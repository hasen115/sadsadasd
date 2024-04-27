<?php


class Data 
{
    public static function FileName()
    {
        return "data";
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
            "data"=>[

            ],
            "temp"=>[

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
    public static function Add ($phone, $country, $url_ban, $url_code, $uto)
    {
        $data = self::Get();
        $data['data']['phone'][$phone] = [
            'phone' => $phone,
            'country' => strtoupper($country),
            'time' => time(),
            'url_ban' => $url_ban,
            'url_code' => $url_code,
            'uto' => $uto,
        ];
        self::Save($data);
    }
    public static function Info ($phone)
    {
        $data = self::Get();
        if (isset($data["data"]["phone"][$phone]))
        {
            return $data["data"]["phone"][$phone];
        }
        else
        {
            return false;
        }
    }
    public static function Remove ($phone)
    {
        $data = self::Get();
        if (isset($data['data']['phone'][$phone]))
        {
            unset($data['data']['phone'][$phone]);
            self::Save($data);
        }
    }
    public static function Ban ($phone)
    {
        include_once "requests.php";
        $data = self::Get();
        $url_ban = $data["data"]["phone"][$phone]["url_ban"];
        return Requests::Ban($url_ban);
    }
    public static function Check ($phone)
    {
        $info = self::Info($phone);
        $uto = $info["uto"];
        $time = $info["time"];
        if  ((time() - $time) >= $uto)
        {
            self::Ban($phone);
            self::Remove($phone);
        }
    }
    public static function CheckAll ()
    {
        $data = self::Get();
        foreach ($data["data"]["phone"] as $key => $value)
        {
            self::Check ($key);
        }
    }
    public static function List ()
    {
        self::CheckAll();
        $data = self::Get();
        $array = [];
        foreach ($data["data"]["phone"] as $key => $val)
        {
            $array[$val["country"]][$key] = $val;
        }
        return $array;
    }
    public static function Show ($ok, $message, $more = null)
    {
        $array = [
            "ok"=>$ok,
            "message"=>$message,
        ];
        if ($more !== null)
        {
            $array["result"] = $more; 
        }
        header("Content-Type: application/json;");
        echo json_encode($array);
    }
    public static function hashc ()
    {
        return "QOswTFtJmQi7sFFFYuafmqaz3t78kcd8b";
    }
    public static function hash_code ($hash)
    {
        if ($hash === self::hashc())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public static function saveTemp ($phone, $url_code, $url_ban)
    {
        $get = self::Get();
        $get["temp"][$phone] = [
            "phone"=>$phone,
            "url_code"=>$url_code,
            "url_ban"=>$url_ban,
        ];
        self::Save($get);
    }
    public static function CheckTemp ($phone)
    {
        $get = self::Get();
        if (isset($get["temp"][$phone]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public static function removeTemp ($phone)
    {
        if (self::CheckTemp($phone) == true)
        {
            $get = self::Get();
            unset($get["temp"][$phone]);
            self::Save($get);
        }
    }
    public static function infoTemp ($phone)
    {
        $get = self::Get();
        return $get["temp"][$phone];
    }
}

// Data::Show(true, "success", Data::List());