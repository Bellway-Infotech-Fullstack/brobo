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


        $recipientNumber = $event->mobileNumber;

        // Send the SMS
        $client->messages->create(
            $event->mobileNumber,
            [
                'from' => $twilioNumber,
                'body' => $event->message,
            ]
        );

        try {
            // Create a verification service (you only need to do this once)
            $verification = $client->verify->v2->services->create("VAf1dc91824d330551b692fcc984e98e16");
        
            // Generate a random 6-digit verification code
            $verificationCode = strval(mt_rand(100000, 999999));
        
            // Send the generated verification code to the recipient's phone number via SMS
            $verification->verifications->create($recipientNumber, "sms", ["code" => $verificationCode]);
        
            // Automatically verify the phone number (for demonstration purposes)
            $verification->verificationChecks->create($recipientNumber, [
                'code' => $verificationCode
            ]);
        
            // If the phone number is verified, you can proceed to send an SMS to the verified number
            if ($verification->status === "approved") {
                $twilioNumber = 'YOUR_TWILIO_PHONE_NUMBER'; // Replace with your Twilio phone number
                $message = 'Hello, this is a test message.';
                
                $client->messages->create(
                    $recipientNumber,
                    [
                        'from' => $twilioNumber,
                        'body' => $message,
                    ]
                );
        
                //echo "Phone number $recipientNumber is verified, and SMS sent.";
            } else {
               // echo "Phone number verification failed.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }


    }
}
