<?php

$update = json_decode(file_get_contents("php://input"));
if(isset($update->message) || isset($update->callback_query)){

	$message      = $update->message;

	$text                = $message->text ?? false;
	$data               = $update->callback_query->data;
	$from              = $message->from ?? $update->callback_query->from;
	$langCode = $from->language_code;
	$chat               = $message->chat ?? $update->callback_query->message->chat;
	$message_id = $message->message_id ?? $update->callback_query->message->message_id;
	$from_id          = $from->id;
	$chat_id           = $chat->id;
	$first_name     = $from->first_name;
	$last_name      = $from->last_name ?? false;
	$username       = $from->username ?? false;
	$type                 = $chat->type;
	$contact           = $message->contact ?? false;
	$caption           = $message->caption ?? null;
	$reply            = $message->reply_to_message ?? false;
}