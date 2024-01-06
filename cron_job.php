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
                'title' => "Due amount pending for order $customOrderId",
                'body' => "Your Rs. $dueAmount is pending . Please pay your remaining amount",
            ];
            
            array_push($allData,$data);
    
         
        }
    }
    
    return $allData; // Output the data
    
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
        $orderId = $row['id'];
        $dueAmount = $row['pending_amount'];
        $customOrderId = $row['order_id'];
         if($dueAmount == 0 && $endDate == $todayDate){
             
            $data = [
                'user_id' => $userId,
                 'title' => 'Order Completed',
                'body' => 'Your order has been completed',
            ];
            
            array_push($allData,$data);
                       
        }
    
       
    }
    
    return $allData; // Output the data
    
    // Close the connection
    mysqli_close($conn);

}


?>