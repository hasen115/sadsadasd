<?php



class Requests
{
    public static function Code ($url_code)
    {
        $req = json_decode(file_get_contents($url_code), true);
        if ($req["code"] == "200")
        {
            return [
                "ok"=>true,
                "code"=>$req["data"]
            ];
        }
        elseif ($req["code"] == "908")
        {
            return [
                "ok"=>true,
                "code"=>null,
            ];
        }
        else
        {
            return [
                "ok"=>false,
                "code"=>null,
            ];
        }

    }
    public static function Ban ($url_ban)
    {
        $req = json_decode(file_get_contents($url_ban), true);
        return [
            "ok"=>true,
        ];
    }
}