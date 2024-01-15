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
    
    $url = "https://fcm.googleapis.com/fcm/send";
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

//sendNotificationOfPendingDueAmountOrder();
sendNotificationOfCompletedOrder();
?>