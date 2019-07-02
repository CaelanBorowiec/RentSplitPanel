<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Services\DBService;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct()
    {
    	$this->dbs = new DBService;
    }

    public function list($bot)
    {
        $bot->reply($this->dbs->getUsers());
    }

    public function replyUID($bot)
    {
        $user = $bot->getUser();

        $bot->reply("Hi " . $user->getFirstName() . ", your UID is: " . $user->getId());
    }
}
