<?php
$content = file_get_contents("php://input");
$update = json_decode($content, true);
if(!$update)
{
  exit;
}
$message = isset($update['message']) ? $update['message'] : "";
$messageId = isset($message['message_id']) ? $message['message_id'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$firstname = isset($message['chat']['first_name']) ? $message['chat']['first_name'] : "";
$lastname = isset($message['chat']['last_name']) ? $message['chat']['last_name'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$date = isset($message['date']) ? $message['date'] : "";
$text = isset($message['text']) ? $message['text'] : "";
$text = trim($text);
$text = strtolower($text);
header("Content-Type: application/json");
$response = '';
/*if(strpos($text, "/start") === 0 || strtolower($text) =="ciao")
{
	$response = "Ciao $firstname, benvenuto!";
}
elseif($text=="domanda 1")
{
	$response = "risposta 1";
}
elseif($text=="domanda 2")
{
	$response = "risposta 2";
}
else
{
	$response = "Comando non valido!";
}*/

//----------------------------
// la mia risposta è un array JSON composto da chat_id, text, method
// chat_id mi consente di rispondere allo specifico utente che ha scritto al bot
// text è il testo della risposta
$parameters = array('chat_id' => $chatId, "text" => $text);
// method è il metodo per l'invio di un messaggio (cfr. API di Telegram)
$parameters["method"] = "sendMessage";
// imposto la inline keyboard
$keyboard = ['inline_keyboard' => [[
	['text' =>  'myTex1t', 'callback_data' => 'myCallbackText1'],
	['text' =>  'myTex2t', 'callback_data' => 'myCallbackText2']	
]]];
$parameters["reply_markup"] = json_encode($keyboard, true);


if ($result->isType('callback_query')) {
    $query = $result->getCallbackQuery();
    $data  = $query->getData();
    $chid = $query->getFrom()->getId();

    // again, you can't get the message object if the object is a callback_query.
    // in this case the $json variable would be undefined.
    $json = json_decode($query->getMessage(), true);
    $telegram->sendMessage([
        'chat_id' => $chid,
        'text' => 'Here is the calzlback: ' . $data,
        'reply_markup' => $keyboard
    ]);

// Just to make sure that there's a ['message']:
} /*elseif(isset($result["message"])) {
    $chat_id = $result["message"]["chat"]["id"];

    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'Hello',
        'reply_markup' => $keyboard
    ]);
}*/





/*$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";*/
echo json_encode($parameters);
