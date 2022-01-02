<?php

require_once 'db.php';

if (isset($_POST['id'])){ //loaded as object so POST method is used i
    $id = $_POST['id'];
    $bid = $_POST['bid'];
    

//works when hard coded, stops working with real escape string. cant find the issue.
    $sql = sprintf(

        "SELECT * FROM auctions WHERE id ='%s'",
        mysqli_real_escape_string($link, $id)


    );

                     
       
  

    

    $result = mysqli_query($link, $sql);
    if (!$result) {
        die("FAILURE" . mysqli_error($link));
    }
$checkPrice = mysqli_fetch_array($result);


  
$realNumber = explode(".",$checkPrice['lastBidPrice']);
echo $id;
}


