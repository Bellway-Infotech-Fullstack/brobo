<?php

namespace App\CentralLogics;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Nexmo\Laravel\Facade\Nexmo;
use Twilio\Rest\Client;

class SMS_module
{
    public static function send($receiver, $otp, $templateId = null,$resend = null)
    {
        // $config = self::get_settings('twilio_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::twilio($receiver, $otp);
        //     return $response;
        // }

        // $config = self::get_settings('nexmo_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::nexmo($receiver, $otp);
        //     return $response;
        // }

        // $config = self::get_settings('2factor_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::two_factor($receiver, $otp);
        //     return $response;
        // }

        // $config = self::get_settings('msg91_sms');
        // if (isset($config) && $config['status'] == 1) {
            $response = self::msg_91($receiver, $otp, $templateId,$resend);
            return $response;
        // }

        // return 'not_found';
    }

    public static function twilio($receiver, $otp)
    {
        $config = self::get_settings('twilio_sms');
        $response = 'error';

        if (isset($config) && $config['status'] == 1) {
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $sid = $config['sid'];
            $token = $config['token'];
            try {
                $twilio = new Client($sid, $token);
                $twilio->messages
                    ->create($receiver, // to
                        array(
                           "from" => $config['from'],
                            "body" => $message
                        )
                    );
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        } elseif (empty($config)) {
            DB::table('business_settings')->updateOrInsert(['key' => 'twilio_sms'], [
                'key' => 'twilio_sms',
                'value' => json_encode([
                    'status' => 0,
                    'sid' => '',
                    'token' => '',
                    'from' => '',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return $response;
    }

    public static function nexmo($receiver, $otp)
    {
        $sms_nexmo = self::get_settings('nexmo_sms');
        $response = 'error';
        if (isset($sms_nexmo) && $sms_nexmo['status'] == 1) {
            $message = str_replace("#OTP#", $otp, $sms_nexmo['otp_template']);
            try {
                $config = [
                    'api_key' => $sms_nexmo['api_key'],
                    'api_secret' => $sms_nexmo['api_secret'],
                    'signature_secret' => '',
                    'private_key' => '',
                    'application_id' => '',
                    'app' => ['name' => '', 'version' => ''],
                    'http_client' => ''
                ];
                Config::set('nexmo', $config);
                Nexmo::message()->send([
                    'to' => $receiver,
                    'from' => $sms_nexmo['from'],
                    'text' => $message
                ]);
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        } elseif (empty($config)) {
            DB::table('business_settings')->updateOrInsert(['key' => 'nexmo_sms'], [
                'key' => 'nexmo_sms',
                'value' => json_encode([
                    'status' => 0,
                    'api_key' => '',
                    'api_secret' => '',
                    'signature_secret' => '',
                    'private_key' => '',
                    'application_id' => '',
                    'from' => '',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return $response;
    }

    public static function two_factor($receiver, $otp)
    {
        $config = self::get_settings('2factor_sms');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $api_key = $config['api_key'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://2factor.in/API/V1/" . $api_key . "/SMS/" . $receiver . "/" . $otp . "",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if (!$err) {
                $response = 'success';
            } else {
                $response = 'error';
            }
        } elseif (empty($config)) {
            DB::table('business_settings')->updateOrInsert(['key' => '2factor_sms'], [
                'key' => '2factor_sms',
                'value' => json_encode([
                    'status' => 0,
                    'api_key' => 'aabf4e9c-f55f-11eb-85d5-0200cd936042',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return $response;
    }

    public static function msg_91($receiver, $otp, $templateId = null, $resend = null)
    {
        $config = self::get_settings('msg91_sms');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {  

        

            if($resend == 'yes'){
              
                $receiver =  str_replace("+",'',$receiver);
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://control.msg91.com/api/v5/otp/retry?authkey=".$config['authkey']."&retrytype=text&mobile=".$receiver."",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_POSTFIELDS => json_encode(array("OTP" => $otp)), // Update the OTP value
                ]);
                  
                  $response = curl_exec($curl);
                  $err = curl_error($curl);
                  
                  curl_close($curl);




                if (!$err) {
                    $response = 'success';
                } else {
                    $response = 'error';
                }
            }

           
            

            else if($templateId == '65eee75bd6fc056f7227ad82'){
                $curl = curl_init();
            
                curl_setopt_array($curl, [
                CURLOPT_URL => "https://control.msg91.com/api/v5/flow/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n  \"template_id\":\"65eee75bd6fc056f7227ad82\",\n  \"short_url\": \"0\",\n  \"recipients\": [\n    {\n      \"mobiles\": \"918982578473\",\n      \"MOBILENUMBER\": \"VALUE 1\",\n      \"PASSWORD\": \"VALUE 2\"\n    }\n  ]\n}",
                CURLOPT_HTTPHEADER => [
                    "authkey: ",
                    "content-type: application/JSON"
                ],
                ]);
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if (!$err) {
                    $response = 'success';
                } else {
                    $response = 'error';
                }
               
            } else if($templateId == '1'){
                $curl = curl_init();

                curl_setopt_array($curl, [
                CURLOPT_URL => "https://control.msg91.com/api/v5/otp/verify?otp=$otp&mobile=$receiver",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "authkey: " . $config['authkey']
                ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {                   
                    $response = 'error';		
               
                } else {
                    $response = $response;
                    
                }
            }
            
            
            
            else {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=" . $templateId . "&mobile=" . $receiver . "&authkey=" . $config['authkey'] . "",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_POSTFIELDS => "{\"OTP\":\"$otp\"}",
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json"
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if (!$err) {
                    $response = 'success';
                } else {
                    $response = 'error';
                }
            } 

            

            



        } elseif (empty($config)) {
            DB::table('business_settings')->updateOrInsert(['key' => 'msg91_sms'], [
                'key' => 'msg91_sms',
                'value' => json_encode([
                    'status' => 0,
                    'template_id' => '',
                    'authkey' => '',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return $response;
    }

    public static function get_settings($name)
    {
        $config = null;
        $data = BusinessSetting::where(['key' => $name])->first();
        if (isset($data)) {
            $config = json_decode($data['value'], true);
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }
}
