<?php require_once 'db.php';
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Items for sale</title>

</head>

<body>

    <div style="text-align: center; font-size:24px">
        <h3>List of items for sale</h3>
        <a href="index.html">Index</a>
        <br />
        <a href="newauction.php">Post an item</a>
        </div>
        <table id="list">
            <?php

            $sql = "SELECT id, itemDescription, itemImagePath, sellersName, lastBidPrice FROM auctions ORDER BY id DESC;";

            $result = mysqli_query($link, $sql);

            if (!$result) {
                die("Something went wrong with query.");
            }
            echo  '
        <tr>
            <th>Item Description</th>
            <th>Image</th>
            <th>Sellers Name</th>
            
            <th>Last Bid Price</th>
        </tr>';

            while ($auction = mysqli_fetch_assoc($result)) {


                $descPre = substr($auction['itemDescription'], 0, 100);
                $descPre .= (strlen($auction['itemDescription']) > strlen($descPre)) ? "..." : "";
                echo ' <tr>
            <td>' . $descPre . '</td>' . '<td><img src="' . $auction['itemImagePath'] . '" width="150">
            <td>' . $auction['sellersName'] . '</td>' . '<td>' . $auction['lastBidPrice'] . '</td>' .
                    '<td><a href="placebid.php?id=' . $auction['id'] . '">Make a bid</a></td>' .
                    '</tr>';
            }


            ?>

        </table>

</body>

</html>