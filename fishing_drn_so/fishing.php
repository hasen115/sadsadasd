<?php



include_once "settings.php";

$settings = Settings::Get();
if ($settings["run"] == true)
{
    include_once "site.php";
    for($i = 0; $i<=5; $i++)
    {
        foreach($settings["countrys"] as $key => $val)
        {
            $super = Site::requestGetPhone($settings["demending"], $settings["app"], strtolower($key));
            if ($super["ok"] == true)
            {
                include_once "compositions.php";
                Compositions::Bot("sendMessage", [
                    "chat_id" => $settings["chB"],
                    "text" => "تم شراء الرقم بنجاح ☑️

📞 الرقم: `".$super["phone"]."` 
♻️ الكود: في الإنتظار...
    ",
                    "parse_mode" => "Markdown",
                    "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [["text" => "📩 ⌯ طلب الكود.", "callback_data" =>"getCode||".$super["hash"]]],
                            [["text" => "⛔️ ⌯ إلغاء الرقم .", "callback_data" =>"getBan||".$super["hash"]]],
                            [["text" => "🔄 - فحص الرقم .", "url" =>"https://wa.me/".$super["phone"]]],
                        ],
                    ]),
                ]);
            }
        }
        
    }

}