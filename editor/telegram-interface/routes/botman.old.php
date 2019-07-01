<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears("What's your name", function ($bot) {
    $bot->reply("I'm FennecBot!");
});

$botman->hears('Start conversation', BotManController::class.'@startConversation');
