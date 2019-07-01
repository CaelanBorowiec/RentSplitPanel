<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class DefaultConversation extends Conversation
{
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
                Button::create('Check a user\'s payment status')->value('userdetails'),
            ]);

        // We ask our user the question.
        return $this->ask($question, function (Answer $answer) {
            // Did the user click on an option or entered a text?
            if ($answer->isInteractiveMessageReply()) {
                // We compare the answer to our pre-defined ones and respond accordingly.
                switch ($answer->getValue()) {
                    case 'listusers':
                        $this->askForBreedName();
                        break;
                    case 'userdetails':
                        $this->askForSubBreed();
                        break;
                }
            }
        });
    }

    /**
     * Ask for the breed name and send the image.
     *
     * @return void
     */
    public function askForBreedName()
    {
        $this->ask('What\'s the breed name?', function (Answer $answer) {
            $name = $answer->getText();

            $this->say((new App\Services\DogService)->byBreed($name));
        });
    }

    /**
     * Ask for the breed name and send the image.
     *
     * @return void
     */
    public function askForSubBreed()
    {
        $this->ask('What\'s the breed and sub-breed names? ex:hound:afghan', function (Answer $answer) {
            $answer = explode(':', $answer->getText());

            $this->say((new App\Services\DogService)->bySubBreed($answer[0], $answer[1]));
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
