<?php

/* 
    جميع الحقوق محفوظة : t.me/zyzzzyz 
*/
class Compositions 
{
    public static function version ()
    {
        return "v10.1";
    }
    public static function Bot ($methode, $datas=[])
    {
        include_once "configuration.php";
        $washli = http_build_query($datas);
        $request = "https://api.telegram.org/bot".Configuration::token()."/".$methode."?".$washli;
        $connection = file_get_contents($request);
        return json_decode($connection);
    }
    public static function Webhook ($url)
    {
        /* 
setWebhook
Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success.

If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.

        */
        $result = self::Bot("setWebhool", [
            "url"=> $url,
        ]);
        return $result;
    }
    public static function WebhookRemove ($drop_pending=false)
    {
        $result = self::Bot("deleteWebhook", [
            "drop_pending_updates"=> $drop_pending,
        ]);
        return $result;
    }
    public static function Webhookinfo ()
    {
        $result = self::Bot("getWebhookInfo");
        return $result;
    }
    public static function getUpdates (int $offset=null, int $limit=null, int $timeout = null, array $allowed_updates=null)
    {
        $array = [];
        if ($offset != null)
        {
            $array["offset"] = $offset;
        }
        if ($limit != null)
        {
            $array["limit"] = $limit;
        }
        if ($timeout != null)
        {
            $array["timeout"] = $timeout;
        }
        if ($allowed_updates != null)
        {
            $array["allowed_updates"] = json_encode($allowed_updates);
        }
        $result = self::Bot(__FUNCTION__, $array);
        return $result;
    }
    /*  
     * for bot functions 
    */
    public static function getMe ()
    {
        $result = self::Bot(__FUNCTION__);
        return $result;
    }
    public static function logOut ()
    {
        $result = self::Bot(__FUNCTION__);
        return $result;
    }
    public static function close ()
    {
        $result = self::Bot(__FUNCTION__);
        return $result;
    }
    public static function sendMessage ( 
        $chat_id, 
         $text, $parse_mode=0, 
        bool $disable_web_page_preview=true, 
        bool $disable_notification=false, 
        bool $protect_content=false, 
         $reply_to_message_id=null, 
        array $reply_markup=null)
    {
        $array = [];
        $array["chat_id"] = $chat_id;
        $array["text"] = $text;
        if ($parse_mode == 0)
        {
            $array["parse_mode"] = "MARKDOWN";
        }
        elseif ($parse_mode == 1)
        {
            $array["parse_mode"] = "HTML";
        }
        else
        {
            $array["parse_mode"] = $parse_mode;
        }
        $array["disable_web_page_preview"] = $disable_web_page_preview;
        $array["disable_notification"] = $disable_notification;
        $array["protect_content"] = $protect_content;
        if ($reply_to_message_id != null)
        {
            $array["reply_to_message_id"] = $reply_to_message_id;
        }
        if ($reply_markup != null)
        {
            $array["reply_markup"] = $reply_markup;
        }
        $result = self::Bot(__FUNCTION__, $array);
        return $result;
    }
    public static function answerCallbackQuery(
         $callback_query_id,
         $text=null,
        bool $show_alert=false,
        string $url=null,
         $cache_time=null
    )
    {
        $array = [];
        $array["callback_query_id"] = $callback_query_id;
        if ($show_alert !== false)
        {
            $array["show_alert"] = $show_alert;
        }
        if ($text !== null) 
        {
            $array["text"] = $text;
        }
        if ($url !== null)
        {
            $array["url"] = $url;
        }
        if ($cache_time !== null) 
        {
            $array["cache_time"] = $cache_time;
        }
        $result = self::Bot(__FUNCTION__, $array);
        return $result;
    }
}


