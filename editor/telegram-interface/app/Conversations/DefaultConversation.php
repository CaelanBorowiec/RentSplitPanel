<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

use App\Services\DBService;


class DefaultConversation extends Conversation
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->DBService = new DBService;
    }
    /**
     * First question to start the conversation.
     *
     * @return void
     */
    public function defaultQuestion()
    {
        // We first create our question and set the options and their values.
        $question = Question::create('Hi, how can I help?')
            ->addButtons([
                Button::create('List users')->value('listusers'),
                Button::create('List a user\'s payments')->value('paymentstatus'),
                Button::create('Set a display name')->value('displayname'),
                Button::create('Add a payment')->value('newpayment'),
                Button::create('Clear all payments')->value('clearpayments'),
                Button::create('Set power cost')->value('setpower'),
            ]);

        // We ask our user the question.
        return $this->ask($question, function (Answer $answer) {
            // Did the user click on an option or entered a text?
            if ($answer->isInteractiveMessageReply()) {
                // We compare the answer to our pre-defined ones and respond accordingly.
                switch ($answer->getValue()) {
                    case 'listusers':
                        $this->say($this->DBService->printUsers());
                        break;
                    case 'paymentstatus':
                        $this->userMenu();
                        break;
                    case 'displayname':
                        $this->updateDisplayName();
                        break;
                    case 'newpayment':
                        $this->addNewPayment();
                        break;
                    case 'clearpayments':
                        $this->DBService->resetPayments();
                        $this->say("Okay, I cleared all stored payments");
                        break;
                        break;
                    case 'setpower':
                        $this->processPowerCost();
                        break;
                }
            }
        });
    }

    public function userMenu()
    {
        $question = Question::create('Which user?')->addButtons($this->DBService->getUserMenuArray(""));

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->say($this->DBService->printUserDetails((int) $answer->getValue()));
            }
        });
    }

    public function updateDisplayName()
    {
        $question = Question::create('Okay, a new name. Which user?')->addButtons($this->DBService->getUserMenuArray(""));
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                //$this->say("You picked ". $answer->getValue());
                $this->target = (int) $answer->getValue();
                $this->ask("Okay, what's the new name?", function (Answer $response) {
                    $this->DBService->setDisplayName( $this->target, $response->getText());
                    $this->say('Cool, I updated the name to ' . $response->getText());
                    $this->say($this->DBService->printUsers());
                });
            }
        });
    }

    public function addNewPayment()
    {
        $question = Question::create('Okay, a payment. Which user?')->addButtons($this->DBService->getUserMenuArray(""));
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                //$this->say("You picked ". $answer->getValue());
                $this->target = (int) $answer->getValue();

                $question = Question::create("Okay, what is the payment for?")->addButtons($this->getPaymentTypes());
                $this->ask($question, function (Answer $answer) {
                    $this->paymentType = $answer->getValue();

                    $this->ask("Okay, how much was paid?", function (Answer $response) {
                        $this->DBService->logPayment( $this->target, $this->paymentType, $response->getText());
                        $this->say('Cool, I logged the payment');
                        //$this->say($this->DBService->printUsers());
                    });
                });
            }
        });
    }
    function getPaymentTypes()
    {
        $types = [ "internet", "rent", "power" ];
        $buttons = [];
        foreach ($types as $type) {
            array_push($buttons, Button::create(ucfirst($type))->value($type));
        }
        return $buttons;
    }

    public function processPowerCost()
    {
        $this->ask("Okay, what's the power bill?", function (Answer $response) {
            $amount = $response->getText();
            $usersCount = $this->DBService->getUserCount();
            $this->DBService->setPowerCost($amount/$usersCount);
            $this->say('Cool, I did the needful, power cost is now ' . $amount/$usersCount  );
        });
    }


    /**
     * Start the conversation
     *
     * @return void
     */
    public function run()
    {
        // This is the boot method, it's what will be excuted first.
        $this->defaultQuestion();
    }
}
