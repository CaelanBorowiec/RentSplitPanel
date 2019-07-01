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
}
