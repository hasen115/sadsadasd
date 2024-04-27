<?php

$array = [
    "hash"=>false,
    "phone"=>false,
    "country"=>false,
    "url_code"=>false,
    "url_ban"=>false,
    "uto"=>false,
];
$demending = [];
include_once "data.php";
foreach ($array as $key => $val)
{
    $array[$key] = true;
    if (isset($_GET[$key]))
    {
        if (!empty($_GET[$key]))
        {
            $demending[$key] = $_GET[$key];
        }
        else
        {
            $array[$key] = false;
            return Data::Show(false, "The '".$key."' is empty", $array);
        }
    }
    else
    {
        $array[$key] = false;
        return Data::Show(false, "The '".$key."' is not isset", $array);
        
    }
}
$hash = Data::hash_code($demending["hash"]);
unset($demending["hash"]);
if ($hash == false)
{
    return Data::show(false, "hash is invalid", $demending);
}
$phone = "+".str_replace(["+", ' '], '', $_GET["phone"]);
if (!preg_match("/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/", $phone))
{
    return Data::Show(false, "Phone is not available", [
        "phone"=>$_GET["phone"],
    ]);
}


Data::Add($phone, $demending["country"],  $demending["url_ban"], $demending["url_code"], $demending["uto"]);
return Data::Show(true, "Phone added successfully .", $demending);