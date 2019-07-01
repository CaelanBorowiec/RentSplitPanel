<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DBService
{
     public function getUsers()
     {
         $users = DB::select('select * from users where disabled = 0');

         return 'yes hi';
     }
}
