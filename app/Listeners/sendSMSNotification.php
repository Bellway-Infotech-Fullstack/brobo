<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;


class sendSMSNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_NUMBER');

        
    
        $client = new Client('SKb6bc209dea44263600a9b0b42fda9c5e','rNss5TVIB6deb5hcG9PSfqxHRXo3mNeH',$twilioSid);
       // echo $event->mobileNumber;
        // Send the SMS
        $client->messages->create(
            $event->mobileNumber,
            [
                'from' => $twilioNumber,
                'body' => $event->message,
            ]
        );
    }
}
