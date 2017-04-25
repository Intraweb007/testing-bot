<?php

namespace App\Http\Controllers;

use App\CheckSsl;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;

class BotManController extends Controller
{
    public function handle()
    {
      //  $botman = BotManFactory::create(env('TELEGRAM_TOKEN'));

        $botman = app(env('TELEGRAM_TOKEN'));

        $botman->hears('ssl-info {url}', function (BotMan $bot, $url) {
            $check = CheckSsl::check($url);
            if($check) {
                $bot->reply($check['issuer']);
                $bot->reply($check['valid']);
                $bot->reply($check['expire']);
            } else {
                $bot->reply('Error! Check domain again');
            }
        });

        $botman->listen();
    }
}
