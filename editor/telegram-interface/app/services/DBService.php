<?php

namespace App\Services;

use Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DBService
{
     public function getUsers()
     {
          $results = DB::table('users')->get();
          $response = "Users:" . "\n";
          foreach ($results as $user) {
               $response .= "(#$user->id) " . ($user->displayName != "" ? $user->displayName : "Unnamed") . "\n";
          }
          //Log::debug( $results );
          return $response;
     }
     public function bySubBreed()
     {
          return "Can't help right now";
     }
}
