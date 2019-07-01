<?php

use App\Conversations\StartConversation;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

//$botman->hears('/help', 'App\Http\Controllers\HelpController@help');
$botman->hears('Start', 'App\Http\Controllers\ConversationController@index');
$botman->hears('Users', 'App\Http\Controllers\UsersController@list');

$botman->fallback('App\Http\Controllers\FallbackController@index');
