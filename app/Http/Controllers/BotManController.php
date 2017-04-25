<?php

namespace App\Http\Controllers;

use App\CheckSsl;
use App\Conversations\ExampleConversation;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;

class BotManController extends Controller
{
    public function handle()
    {
        $config = [
            'telegram_token' => '318862221:AAE36OHQRpABx_4rXQ5gZyd8k1v8jnDVi_g'
        ];

// create an instance
        $botman = BotManFactory::create($config);


    	//$botman = app('botman');

      //  $botman->verifyServices(env('TOKEN_VERIFY'));

        $botman->hears('sss-info {url}', function (BotMan $bot, $url) {
            $check = CheckSsl::check($url);
            if($check){
                $bot->reply($check['issuer']);
                $bot->reply($check['valid']);
                $bot->reply($check['expire']);
            } else{
                $bot->reply('Error! Check domain again');
            }
        });

        $botman->listen();
        dd($botman);
    }

    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
