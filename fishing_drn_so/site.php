<?php


class Site
{
    public static function domain ()
    {
        return "duraincloud.com";
    }
    public static function apiUrl ()
    {
        return "https://api.".self::domain()."/out/ext_api";
    }
    public static function request ($methode, $datas=[])
    {
        $params = http_build_query($datas);
        $url = self::apiUrl()."/$methode?".$params;
        $result = file_get_contents($url);
        echo $result."\n";
        return $result;
    }
    public static function requestGetPhone ($params, $app, $country)
    {
        $params["pid"] = $app;
        $params["cuy"] = $country;
        $result = self::request("getMobile", $params);
        $result = json_decode($result, true);
        if ($result["code"] == 200)
        {
            return [
                "ok"=> true,
                "phone"=>$result["data"],
                "hash"=>$result["data"]."#"."$app",
                "status"=>"success",
            ];
        }
        else 
        {
            
            return [
                "ok"=> false,
                "because"=> $result["code"],
            ];
            
        }
    }
    public static function requestGetCancel ($params, $hash)
    {
        $hashing = explode("#", $hash);
        $params["pn"] = $hashing[0];
        $params["pid"] = $hashing[1];
        $result = self::request("passMobile", $params);
        $result = json_decode($result, true);
        if ($result["code"] == 200)
        {
            return [
                "ok"=> true,
                "status"=>"cancelled",
            ];
        }
    }
    public static function requestGetCode ($params, $hash)
    {
        $hashing = explode("#", $hash);
        $params["pn"] = $hashing[0];
        $params["pid"] = $hashing[1];
        $result = self::request("getMsg", $params);
        $result = json_decode($result, true);
        if ($result["code"] === 200)
        {
            return [
                "ok"=> true,
                "wait"=>false,
                "code"=> $result["data"],
            ];
        }
        elseif ($result["code"] == 908)
        {
            return [
                "ok"=> true,
                "wait"=>true,
            ];
        }
        else
        {
            return [
                "ok"=> false
            ];
        }
    }
}