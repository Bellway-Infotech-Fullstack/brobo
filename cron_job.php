<?php
require_once('db_connect.php');  // Include the database connection file


function sendNotificationOfPendingDueAmountOrder(){
    $conn = connectToDatabase();
    $query = "SELECT o.user_id,u.fcm_token,o.order_id,o.id,o.pending_amount  FROM `orders` AS o INNER JOIN `users` AS u ON o.user_id = u.id WHERE o.status = 'ongoing' AND o.pending_amount > 0 ";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo("Error in SQL query: " . mysqli_error($conn));
    }
    
    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];
        $userFcmToken = $row['fcm_token'];
        $customOrderId = $row['order_id'];
        $orderId = $row['id']; 
        $dueAmount =  $row['pending_amount']; 
        $data = [
            'user_id' => $userId,
            'order_id' => $customOrderId,
            'title' => "Due amount pending for order no, #$customOrderId",
            'body' => "Your Rs. $dueAmount is pending . Please pay your remaining amount",
        ];

        sendPushNotifcation($userFcmToken,$data);
        
    }
    
    // Close the connection
    mysqli_close($conn);

}



function sendNotificationOfCompletedOrder(){

    
    $conn = connectToDatabase();
    
    $query = "SELECT o.user_id,u.fcm_token,o.order_id,o.id  FROM `orders` AS o INNER JOIN `users` AS u ON o.user_id = u.id WHERE o.status = 'ongoing' AND o.pending_amount = 0 AND DATE(o.end_date) = CURDATE()";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo("Error in SQL query: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];
        $userFcmToken = $row['fcm_token'];
        $customOrderId = $row['order_id'];
        $orderId = $row['id']; 
        /*$description = "Your order has been completed. . We hope you enjoy our services. If you have any questions or concerns, feel free to reach out. We appreciate your business and look forward to serving you again in the future";
        $query = "UPDATE `orders` set `status` = 'completed' and `description` = $description where `id` = $orderId";

        $result = mysqli_query($conn, $query);*/

        $data = [
            'user_id' => $userId,
             'title' => 'Order Completed',
             'body' => "Order No. # $customOrderId has been completed",
        ];

        sendPushNotifcation($userFcmToken,$data);
     }
    // Close the connection
    mysqli_close($conn);

}


function sendPushNotifcation($fcmToken, $data){
    
   
    
     
     $conn = connectToDatabase();
     
    $query = "SELECT * FROM `business_settings` WHERE `key` = 'push_notification_key'";
  
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo("Error in SQL query: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);

    $key = $row['value'];
    
/*    $url = "https://fcm.googleapis.com/fcm/send";
    $header = array("authorization: key=" . $key . "",
        "content-type: application/json"
    );
   
    $postdata = '{
        "to" : "' . $fcmToken . '",
        "mutable_content": true,
        "data" : {
            "title":"' . $data['title'] . '",
            "body" : "' . $data['body'] . '",
            "order_id":"' . $data['order_id'] . '",
            "is_read": 0
        },
        "notification" : {
            "title" :"' . $data['title'] . '",
            "body" : "' . $data['body'] . '",
            "order_id":"' . $data['order_id'] . '",
            "title_loc_key":"' . $data['order_id'] . '",
            "is_read": 0,
            "icon" : "new",
            "sound" : "default"
        }
    }';*/


  //$token = $this->generateAccessToken($serviceAccountKeyFile);
          $url =  "https://fcm.googleapis.com/v1/projects/brobo-99cc7/messages:send";
          
                    $serviceAccountKeyFile = json_decode(file_get_contents('/var/www/html/cp/app/CentralLogics/service-account-file.json'), true);
                    
       //                       $token = Helpers::generateAccessToken($serviceAccountKeyFile);


    
$token = $this->generateAccessToken($serviceAccountKeyFile);


      $header = array("authorization: Bearer " . $token . "",
            "content-type: application/json"
           );
           
           $postdata = '{
        "message": {
            "token": "' . $fcm_token . '",
            "notification": {
                 "body": "' . $data['body'] . '",
                 "title": "' . $data['title'] . '",

            },
            "android":{
                "notification": {
                 "body": "' . $data['body'] . '",
                 "title": "' . $data['title'] . '",
                  "sound" : "default"
            },


            }
        }
    }';



    $ch = curl_init();
    $timeout = 120;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Get URL content
    $result = curl_exec($ch);
    // close handle to release resources
    curl_close($ch);
            
   
    // return $result;
}


 function generateAccessToken($serviceAccountKeyFile)
{
    $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $now = time();
    $expiry = $now + 3600; // Token valid for 1 hour
  $jwtClaimSet = base64_encode(json_encode([
        'iss' => $serviceAccountKeyFile['client_email'],
        'scope' => 'https://www.googleapis.com/auth/cloud-platform', // Replace with your required scope
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $expiry,
    ]));
    
     $jwtSignatureInput = $jwtHeader . '.' . $jwtClaimSet;
    $signature = '';
    $privateKey =  $serviceAccountKeyFile['private_key'];
    openssl_sign($jwtSignatureInput, $signature, $privateKey, 'sha256');

    $jwt = $jwtSignatureInput . '.' . base64_encode($signature);
    $token_url = 'https://oauth2.googleapis.com/token';
    $post_fields = [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt,
    ];
    
     $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    $response = curl_exec($ch);
    curl_close($ch);
    $token_info = json_decode($response, true);
    if (isset($token_info['access_token'])) {
        return $token_info['access_token'];
    } else {
        return 'Error retrieving access token';
    }
    
   }


sendNotificationOfPendingDueAmountOrder();
sendNotificationOfCompletedOrder();
?>