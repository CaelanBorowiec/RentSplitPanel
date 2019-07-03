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
               $response .= " $user->id. " . ($user->displayName != "" ? $user->displayName : "Unnamed") . "\n";
          }
          //Log::debug( $results );
          return $response;
     }

     public function getUserMenuArray($cmd="")
     {
          $results = DB::table('users')->get();
          $users = [];
          foreach ($results as $user) {
               array_push($users, Button::create("$user->id. [$user->displayName]")->value($cmd . $user->id));
          }
          return $users;
     }

     public function printUserDetails($id)
     {
        $results = DB::select('SELECT payments.type, payments.amount, users.displayName FROM payments INNER JOIN users ON payments.user = users.id where payments.user = :id', ['id' => $id]);

        if (sizeof($results) == 0)
          return "This user has not made any payments";

        $paymentsTypes = [];
        $paymentsMade = [];
        // Count the amounts paid
        foreach ($results as $payment) {
             if (!isset($paymentsMade[$payment->type]))
             {
                  $paymentsMade[$payment->type] = 0;
                  array_push($paymentsTypes, $payment->type);
             }
             $paymentsMade[$payment->type] += $payment->amount;
        }

        // Build the message
        $message = $results[0]->displayName . " has paid:\n";
        foreach ($paymentsTypes as $type) {
             $message .= " - $" . $paymentsMade[$type] . " for " . $type . "\n";
        }

        return $message;
     }

     public function setDisplayName($id, $name)
     {
          DB::table('users')
               ->where('id', $id)
               ->update(['displayName' => $name]);
     }

     public function logPayment($id, $type, $amount)
     {
          DB::table('payments')->insert([
               'user' => $id,
               'type' => $type,
               'amount' => $amount,
               'date' => date("Y-m-d H:i:s")
          ]);
     }

     public function resetPayments()
     {
       DB::table('payments')->truncate();
     }
}
