<?php

use App\Conversations\StartConversation;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

//$botman->hears('/help', 'App\Http\Controllers\HelpController@help');
$botman->hears('/start', 'App\Http\Controllers\ConversationController@index');
$botman->hears('/users', 'App\Http\Controllers\UsersController@list');
$botman->hears('/getuid', 'App\Http\Controllers\UsersController@replyUID');

$botman->fallback('App\Http\Controllers\FallbackController@index');
