<?php require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#keyupevent").keyup(function(event) {

                var bid = $("#keyupevent").val();
                var id = $("#hidden").val();

                $("#checkIfTooLow").load("isbidtoolow.php", {
                    bid: bid,
                    id: id
                });
            });
        });
    </script>
    <title>Place Bid</title>

</head>

<body>



    <?php

    //didnt have time to crop screenshots, sorry


    
    if (isset($_POST['bidderName'])) { //STATE 1: receiving

//if session is set, can just post new bid without entering name again
        if (isset($_SESSION['bidderName'])) {
            
            $id = $_GET['id'];
            $bidderName = $_SESSION['bidderName'];
            $bidderEmail = $_SESSION['bidderEmail'];
            $newBid = $_POST['newBid'];

            $sql = sprintf(
                "UPDATE auctions SET lastBidPrice ='%s', lastBidderName = '%s', lastBidderEmail = '%s' WHERE id='%s'",
                mysqli_real_escape_string($link, $newBid),
                mysqli_real_escape_string($link, $bidderName),
                mysqli_real_escape_string($link, $bidderEmail),
                mysqli_real_escape_string($link, $id)
            );
            if (!mysqli_query($link, $sql)) {
                die("Fatal error: failed to execute SQL query: " . mysqli_error($link));
            }
            echo "<p>Your bid has been registered.</p>";
        } else if (!isset($_SESSION['bidderName'])){
        

        $errorList = array();
        $id = $_GET['id'];        
        $bidderName= $_POST['bidderName'];
        $bidderEmail = $_POST['bidderEmail'];
        $newBid = $_POST['newBid'];
       
        if (
            //|| !preg_match('/[a-zA-Z]+/', $bidderName) !== 1, couldnt get working in time
            strlen($bidderName) < 2 || strlen($bidderName) > 100 
        ) {
            $errorList[] = "Name must be between 2 and 100 characters, letters only.";
        } else {
            $_SESSION['bidderName'] = $_POST['bidderName'];
        }

        if (!filter_var($bidderEmail, FILTER_VALIDATE_EMAIL)) {
            $errorList[] = "Email failed validation. Please double check.";
        } else {

            $_SESSION["bidderEmail"] = $_POST['bidderEmail'];
        }
        //cant get to work  || !preg_match("/^\$?(?!0.00)(([0-9]{1,3},([0-9]{3},)*)[0-9]{3}|[0-9]{1,3})(\.[0-9]{2})?$/", $startBid)
        if (empty($newBid) || strlen($newBid) > 10) {
            $errorList[] = "Please enter a numeric value. 10 digits max.";
        }
        if ($errorList) { // STATE 2: failed submission
            echo '<ul class="errorMessage">';
            foreach ($errorList as $error) {
                echo "<li>$error</li>\n";
            }
            echo '</ul>';
        } else { // STATE 3: submission successful


            

            $sql = sprintf(
                "UPDATE auctions SET lastBidPrice ='%s', lastBidderName = '%s', lastBidderEmail = '%s' WHERE id='%s'",
                mysqli_real_escape_string($link, $newBid),
                mysqli_real_escape_string($link, $bidderName),
                mysqli_real_escape_string($link, $bidderEmail),
                mysqli_real_escape_string($link, $id)                               
            );
            if (!mysqli_query($link, $sql)) {
                die("Fatal error: failed to execute SQL query: " . mysqli_error($link));
            }
            echo "<p>Your bid has been registered.</p>";
       
        }
    }
    } 
    



    function displayForm()
    {

        $form = <<< END
    <form method="POST" id="biddingForm" enctype='multipart/form-data'>
        
        Your name: <input name="bidderName" type="text"><br>
        Your email: <input name="bidderEmail" type="text">
        <br><br><br>
        Your bid (ex. 24.99): 
        <input type="text" id="keyupevent" name="newBid"  /><br>
         
  <br>
   
        <input type="submit" id="submitBtn" value="Post Bid">
    </form>
    
END;
        echo $form;
    }


                        $id = $_GET['id'];
    
    $sql = sprintf(
        "SELECT itemDescription, itemImagePath, sellersName, lastBidPrice FROM auctions WHERE id ='%s'",
        mysqli_real_escape_string($link, $id)
    );
    $result = mysqli_query($link, $sql);
    if (!$result) {
        die("SQL Query failed: " . mysqli_error($link));
    }
    $viewAuction = mysqli_fetch_assoc($result);




    ?>

    <div class="auctionDisplay">
        <?php
        if ($viewAuction) {

            echo '<h2>' . ($viewAuction['itemDescription']) . '</h2>';

            echo '<i> Posted by ' . $viewAuction['sellersName'] .  "</i>\n<br><br>";
            echo '<div> <img src="' . $viewAuction['itemImagePath'] . '" width="150">' . '</div>';
            echo '<br><div id="price">Last bid price: <span>' . $viewAuction['lastBidPrice'] . '$</span></div>';
            displayForm();
        } else {
            echo '<h2>Auction not found!</h2>';
        }
        ?>
    </div>

    <div id="checkIfTooLow" style="text-align: center; margin: 0 auto;"></div>
    <div id="hidden"><?php  $id = $_GET['id']; echo $id ?></div>



</body>

</html>