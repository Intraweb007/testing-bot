<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('test', function($bot){
    $bot->reply('hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');