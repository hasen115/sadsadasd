<?php

include_once "compositions.php";
include_once "var.php";
include_once "configuration.php";
include_once "settings.php";

$keyboard = [
    [["text"=>"🛅- رفع معلومات الموقع .", "callback_data"=>"upKey"]],
    [["text"=>"حذف دولة", "callback_data"=>"dnCountry"], ["text"=>"🛒 - اضافة دوله.", "callback_data"=>"upCountry"]],
    [["text"=>"🛃⁞ رفع التطبيق", "callback_data"=>"upApp"]],
    [["text"=>"🗑 - حذف معلومات الموقع .", "callback_data"=>"dnKey"]],
    [["text"=>"♻️ ⁞ رفع قناة الاثباتات", "callback_data"=>"upChA"], ["text"=>"💠 ⁞ رفع قناة الصيد", "callback_data"=>"upChB"]],
    [["text"=>"🟢تشغيل الصيد🟢", "callback_data"=>"on"], ["text"=>"🔴ايقاف الصيد🔴", "callback_data"=>"off"]],
];
$back = [
    [["text"=>"رجوع", "callback_data"=>"home"]],
];
$settings = Settings::Get();
if ($text == "/start")
{
    if (in_array($chat_id, Configuration::Admins()))
    {
        
        if ($settings["run"] == true)
        {
            $run_status = "يعمل 🟢";
        }
        elseif ($settings["run"] == false)
        {
            $run_status = "متوقف 🔴";
        }
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"👨‍✈️ ⁞ مرحبا ب : [$first_name](tg://user?id=$chat_id) في بوت الصيد ...\n- حالة الصيد : $run_status .\n\n🎬︙قم بالتحكم بالبوت الأن عبر الضعط على الأزرار",
            "parse_mode"=>"MARKDOWN",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>$keyboard,
            ]),
        ]);

    }
}
if ($data == "home")
{
    Compositions::bot("editMessageText", [
        "chat_id"=>$chat_id,
        "message_id"=>$message_id,
        "text"=>"👨‍✈️ ⁞ مرحبا ب : [$first_name](tg://user?id=$chat_id) في بوت الصيد ...\n- حالة الصيد : $run_status .\n\n🎬︙قم بالتحكم بالبوت الأن عبر الضعط على الأزرار",
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$keyboard,
        ]),
    ]);
}
if ($data == "dnCountry")
{
    $array = [];
    foreach($settings["countrys"] as $key => $value)
    {
        if ($value === true)
        {
            $array[] = [["text"=>"$key", "callback_data"=>"dnCThis||$key"]]; 
        }
    }
    $count_demending = count($array);
    if ($count_demending > 0)
    {
        $array[] = $back[0];
        Compositions::bot("editMessageText", [
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "text"=>"يوجد $count_demending لحذفه ... اضغط عليه ",
            "parse_mode"=>"MARKDOWN",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>$array,
            ]),
        ]);
    }
    else
    {
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"لا يوجد متطلبات ✅",
            "parse_mode"=>"MARKDOWN",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>$back,
            ]),
        ]);
    }

}
if (explode("||", $data)[0] == "getCode")
{
    $hashing = explode("||", $data)[1];
    $msgtxt = $update->callback_query->message->text;
    include_once "site.php";
    $code = Site::requestGetCode($settings["demending"], $hashing);
    if ($code["ok"] == true && $code["wait"] == false)
    {
        Compositions::bot("editMessageText", [
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "text"=>$msgtxt."\n\n code : `".$code["code"]."`",
            "parse_mode"=>"MARKDOWN",
        ]);
    }
    else
    {
        Compositions::answerCallbackQuery($update->callback_query->id, "لم يصل الكود بعد", true);
    }
}
if (explode("||", $data)[0] == "getBan")
{
    $hashing = explode("||", $data)[1];
    $msgtxt = $update->callback_query->message->text;
    include_once "site.php";
    $code = Site::requestGetCancel($settings["demending"], $hashing);
        Compositions::bot("editMessageText", [
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "text"=>"تم حظر الرقم بنجاح",
            "parse_mode"=>"MARKDOWN",
        ]);

}
if ($data == "off")
{
    if ($settings["run"] == false)
    {
        Compositions::answerCallbackQuery($update->callback_query->id, "الصيد بالفعل متوقف", true);
        return false;
    }
    $settings["run"] = false;
    Settings::Save($settings);
    Compositions::bot("sendMessage", [
        "chat_id"=>$chat_id,
        "text"=>"تم ايقاف الصيد بنجاح✅",
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if ($data == "on")
{
    if ($settings["run"] == true)
    {
        Compositions::answerCallbackQuery($update->callback_query->id, "الصيد بالفعل يعمل", true);
        return false;
    }
    if ($settings["chB"] == null)
    {
        Compositions::answerCallbackQuery($update->callback_query->id, "قناة الصيد غير موجودة", true);
        return false;
    }
    $array = [];
    foreach($settings["demending"] as $key => $value)
    {
        if ($value === null)
        {
            $array[] = [["text"=>"$key", "callback_data"=>"upThis||$key"]]; 
        }
    }
    $count_demending = count($array);
    if ($count_demending > 0)
    {
        Compositions::answerCallbackQuery($update->callback_query->id, "يوجد $count_demending ضروريه لبدأ الصيد", true);
        return false;
    }
    else
    {
        $settings["run"] = true;
        Settings::Save($settings);
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"تم تشغيل الصيد بنجاح✅",
            "parse_mode"=>"MARKDOWN",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>$back,
            ]),
        ]);
    }
}
if ($data == "upKey")
{
    $array = [];
    foreach($settings["demending"] as $key => $value)
    {
        if ($value === null)
        {
            $array[] = [["text"=>"{$key}", "callback_data"=>"upThis||$key"]]; 
        }
    }
    $count_demending = count($array);
    if ($count_demending > 0)
    {
        $array[] = $back[0];
        Compositions::bot("editMessageText", [
            "chat_id"=>$chat_id,
            "message_id"=>$message_id,
            "text"=>"يوجد $count_demending لإضافته ... اضغط عليه ",
            "parse_mode"=>"MARKDOWN",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>$array,
            ]),
        ]);
    }
    else
    {
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"لا يوجد متطلبات ✅",
            "parse_mode"=>"MARKDOWN",
            "reply_markup"=>json_encode([
                "inline_keyboard"=>$back,
            ]),
        ]);
    }
}
if ($data == "dnKey")
{
    foreach($settings["demending"] as $key => $value)
    {
        $settings["demending"][$key] = null;
    }
    Settings::Save($settings);
    Compositions::bot("editMessageText", [
        "chat_id"=>$chat_id,
        "message_id"=>$message_id,
        "text"=>"تم حذف جميع المتطلبات بنجاح",
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if (explode("||", $data)[0] == "upThis")
{
    $settings["com"] = $data;
    Settings::Save($settings);
    Compositions::bot("sendMessage", [
        "chat_id"=>$chat_id,
        "text"=>"أرسل المتطلب : ".explode("||", $data)[1] ,
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if (explode("||", $data)[0] == "dnCThis")
{
    unset($settings["countrys"][explode("||", $data)[1]]);
    Settings::Save($settings);
    Compositions::bot("sendMessage", [
        "chat_id"=>$chat_id,
        "text"=>"تم حذف الدولة : ".explode("||", $data)[1] ,
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if ($data == "upApp")
{
    $settings["com"] = $data;
    Settings::Save($settings);
    Compositions::bot("sendMessage", [
        "chat_id"=>$chat_id,
        "text"=>"أرسل رمز التطبيق مثال: 1071 " ,
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if ($data == "upCountry")
{
    $settings["com"] = $data;
    Settings::Save($settings);
    Compositions::bot("sendMessage", [
        "chat_id"=>$chat_id,
        "text"=>"أرسل رمز الدولة مثال: YE " ,
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if ($data == "upChB")
{
    $settings["com"] = $data;
    Settings::Save($settings);
    Compositions::bot("sendMessage", [
        "chat_id"=>$chat_id,
        "text"=>"أرسل ايدي قناة الصيد تبدأ ب -100 مثال: -10023483664883 " ,
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if ($data == "upChA")
{
    $settings["com"] = $data;
    Settings::Save($settings);
    Compositions::bot("sendMessage", [
        "chat_id"=>$chat_id,
        "text"=>"أرسل ايدي قناة الاثباتات تبدأ ب -100 مثال: -10023483664883 " ,
        "parse_mode"=>"MARKDOWN",
        "reply_markup"=>json_encode([
            "inline_keyboard"=>$back,
        ]),
    ]);
}
if ($text && $settings["com"] !== null)
{
    if (explode("||", $settings["com"])[0] == "upThis")
    {
        $settings["demending"][explode("||", $settings["com"])[1]] = $text;
        $settings["com"] = null;
        Settings::Save($settings);
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"تم حفظ المتطلب : ".explode("||", $settings["com"])[1]." بنجاح .\n\n$text" ,
            "parse_mode"=>"MARKDOWN",
        ]);
    }
    if ($settings["com"] == "upApp")
    {
        $settings["app"] = $text;
        $settings["com"] = null;
        Settings::Save($settings);
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"تم حفظ الرمز بنجاح .\n\n$text" ,
            "parse_mode"=>"MARKDOWN",
        ]);
    }
    if ($settings["com"] == "upCountry")
    {
        if (!isset($settings["countrys"][strtoupper($text)]))
        {
            if (!is_numeric($text))
            {
                $settings["countrys"][strtoupper($text)] = true;
                $settings["com"] = null;
                Settings::Save($settings);
                Compositions::bot("sendMessage", [
                    "chat_id"=>$chat_id,
                    "text"=>"تم حفظ الدولة بنجاح .\n\n$text" ,
                    "parse_mode"=>"MARKDOWN",
                ]);
            }
            else
            {
                Compositions::bot("sendMessage", [
                    "chat_id"=>$chat_id,
                    "text"=>"رمز الدولة خاطئ ... \n\n$text" ,
                    "parse_mode"=>"MARKDOWN",
                ]);
            }
        }
        else
        {
            Compositions::bot("sendMessage", [
                "chat_id"=>$chat_id,
                "text"=>"الدولة مضافة مسبقا ... \n\n$text" ,
                "parse_mode"=>"MARKDOWN",
            ]);
        }
    }
    if ($settings["com"] == "upChB")
    {
        $settings["chB"] = $text;
        $settings["com"] = null;
        Settings::Save($settings);
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"تم حفظ القناة بنجاح .\n\n$text" ,
            "parse_mode"=>"MARKDOWN",
        ]);
    }
    if ($settings["com"] == "upChA")
    {
        $settings["chA"] = $text;
        $settings["com"] = null;
        Settings::Save($settings);
        Compositions::bot("sendMessage", [
            "chat_id"=>$chat_id,
            "text"=>"تم حفظ القناة بنجاح .\n\n$text" ,
            "parse_mode"=>"MARKDOWN",
        ]);
    }
    
}
