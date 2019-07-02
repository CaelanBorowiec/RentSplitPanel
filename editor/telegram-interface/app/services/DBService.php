<?php

namespace App\Services;

use Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class DBService
{
     public function printUsers()
     {
          $results = DB::table('users')->get();
          $response = "Users:" . "\n";
          foreach ($results as $user) {
               $response .= "(#$user->id) " . ($user->displayName != "" ? $user->displayName : "Unnamed") . "\n";
          }
          //Log::debug( $results );
          return $response;
     }
     public function getUserMenuArray($cmd="")
     {
          $results = DB::table('users')->get();
          $users = [];
          foreach ($results as $user) {
               array_push($users, Button::create("#$user->id: [$user->displayName]")->value($cmd . $user->id));
          }
          return $users;
     }
}
