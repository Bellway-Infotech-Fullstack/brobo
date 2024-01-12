<?php


function sendNotificationOfPendingDueAmountOrder(){
    // Assuming you have a database connection
    $host = "localhost";
    $user = "u929863268_brobo";
    $password = "47/vjTXh5/";
    $database = "u929863268_brobo";
    
    // Create connection
    $conn = mysqli_connect($host, $user, $password, $database);
    
    // Check connection
    if (!$conn) {
        echo("Connection failed: " . mysqli_connect_error());
    }
    
    
    
    // Get ongoing orders
    $query = "SELECT * FROM orders WHERE status = 'ongoing'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo("Error in SQL query: " . mysqli_error($conn));
    }
    $allData = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];
        $userDataQuery = "SELECT * FROM users WHERE id = $userId";
        $userDataResult = mysqli_query($conn, $userDataQuery);
    
        if (!$userDataResult) {
            echo("Error in SQL query: " . mysqli_error($conn));
        }
    
        $userData = mysqli_fetch_assoc($userDataResult);
        $userFcmToken = $userData['fcm_token'];
        $todayDate = date("Y-m-d");
        $endDate = $row['end_date'];
        $orderId = $row['id'];
        $dueAmount = $row['pending_amount'];
        $customOrderId = $row['order_id'];
    
        if ($dueAmount > 0) {
       
            
            $data = [
                'user_id' => $userId,
                'order_id' => $customOrderId,
                'title' => "Due amount pending for order no, #$customOrderId",
                'body' => "Your Rs. $dueAmount is pending . Please pay your remaining amount",
            ];

            sendPushNotifcation($userFcmToken,$data);
            
           // array_push($allData,$data);
    
         
        }
    }
    
    //return $allData; // Output the data
    
    // Close the connection
    mysqli_close($conn);

}



function sendNotificationOfCompletedOrder(){
    // Assuming you have a database connection
    $host = "localhost";
    $user = "u929863268_brobo";
    $password = "47/vjTXh5/";
    $database = "u929863268_brobo";
    
    // Create connection
    $conn = mysqli_connect($host, $user, $password, $database);
    
    // Check connection
    if (!$conn) {
        echo("Connection failed: " . mysqli_connect_error());
    }
    
    
    
    // Get ongoing orders
    $query = "SELECT * FROM orders WHERE status = 'ongoing'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo("Error in SQL query: " . mysqli_error($conn));
    }
    $allData = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['user_id'];
        $userDataQuery = "SELECT * FROM users WHERE id = $userId";
        $userDataResult = mysqli_query($conn, $userDataQuery);
    
        if (!$userDataResult) {
            echo("Error in SQL query: " . mysqli_error($conn));
        }
    
        $userData = mysqli_fetch_assoc($userDataResult);
        $userFcmToken = $userData['fcm_token'];
        $todayDate = date("Y-m-d");
        $endDate = $row['end_date'];
        $dueAmount = $row['pending_amount'];
        $customOrderId = $row['order_id'];
         if($dueAmount == 0 && $endDate == $todayDate){
             
            $data = [
                'user_id' => $userId,
                 'title' => 'Order Completed',
                 'body' => "Order No. # $customOrderId has been completed",
            ];

            sendPushNotifcation($userFcmToken,$data);
            
           // array_push($allData,$data);
                       
        }
    
       
    }

    
    
   // return $allData; // Output the data
    
    // Close the connection
    mysqli_close($conn);

}


function sendPushNotifcation($fcmToken, $data){

     // Assuming you have a database connection
     $host = "localhost";
     $user = "u929863268_brobo";
     $password = "47/vjTXh5/";
     $database = "u929863268_brobo";
     
     // Create connection
     $conn = mysqli_connect($host, $user, $password, $database);
     
     // Check connection
     if (!$conn) {
         echo("Connection failed: " . mysqli_connect_error());
     }
      // Get ongoing orders
    $query = "SELECT * FROM business_settings WHERE key = 'push_notification_key'";
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

sendNotificationOfPendingDueAmountOrder();
sendNotificationOfCompletedOrder();
?>