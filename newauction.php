<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Post new auction</title>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea'
        });
    </script>


</head>

<body>
    <div class="nav">
        <a href="index.html">Index</a>
        <a href="listitems.php">View other items</a>
    </div>
   
    <?php



    function displayForm()
    {

        $form = <<< END
    <form method="POST" id="sellerForm" enctype='multipart/form-data'>
        
        Your name: <input name="sellerName" type="text"><br>
        Your email: <input name="sellerEmail" type="text"><br>
        Item description (2-1000 characters):
        <textarea id="mytextarea" name="itemDesc"></textarea><br>
          Image of item for sale: <input type="file" name="photo" /><br>
                    <br>
                    Initial bid (ex. 24.99):
  <input type="text" name="startBid"  />
  <br>
   
        <input type="submit" id="submitBtn" value="Submit">
    </form>
END;
        echo $form;
    }
    function verifyUploadedPhoto(&$newFilePath)
    {
        $photo = $_FILES['photo'];

        if ($photo['error'] != UPLOAD_ERR_OK) {
            return "Error uploading photo " . $photo['error'];
        }
        if ($photo['size'] > 2 * 1024 * 1024) { // 2MB
            return "File too big. 2MB max is allowed.";
        }
        $info = getimagesize($photo['tmp_name']);

        if ($info[0] < 100 || $info[0] > 1000 || $info[1] < 100 || $info[1] > 1000) {
            return "Width and height must be within 200-1000 pixels range";
        }
        $ext = "";
        switch ($info['mime']) {
            case 'image/jpeg':
                $ext = "jpg";
                break;
            case 'image/gif':
                $ext = "gif";
                break;
            case 'image/png':
                $ext = "png";
                break;
            default:
                return "Only JPG, GIF, and PNG file types are accepted";
        }
        $target_path = basename($_FILES["photo"]["name"]);
        $underscore_path = str_replace(' ', '_', $target_path);
        $newFilePath = "uploads/" . $underscore_path;
        return TRUE;
    }

    if (isset($_POST['sellerName'])) { // receving a submission
        $sellerName = $_POST['sellerName'];
        $sellerEmail = $_POST['sellerEmail'];
        $itemDesc = $_POST['itemDesc'];
        $startBid = $_POST['startBid'];

        $itemDesc = strip_tags($itemDesc, "<p><ul><li><em><strong><i><b><ol><hr><br><span>");

        // verify inputs
        $errorList = array();
        if (
            strlen($sellerName) < 2 || strlen($sellerName) > 10000
        ) {
            $errorList[] = "Name must be between 2 and 100 characters.";
        }
        if (strlen($itemDesc) < 2 || strlen($itemDesc) > 100) {
            $errorList[] = "Please keep your description between 2 and 1000 characters.";
        }
        if (!filter_var($sellerEmail, FILTER_VALIDATE_EMAIL)) {
            $errorList[] = "Email failed validation. Please double check.";
        }
        //cant get to work || !preg_match("/^\$?(?!0.00)(([0-9]{1,3},([0-9]{3},)*)[0-9]{3}|[0-9]{1,3})(\.[0-9]{2})?$/", $startBid)
        if (empty($startBid) || strlen($startBid) > 10)
         {
            $errorList[] = "Please enter a numeric value. 10 digits max.";
        }

        displayForm();

        $photoFilePath = null;
       
        $val = verifyUploadedPhoto($photoFilePath);
        if ($val !== TRUE) {
            $errorList[] = $val;
        }

        if ($errorList) { // STATE 2: failed submission
            echo '<ul class="errorMessage">';
            foreach ($errorList as $error) {
                echo "<li>$error</li>\n";
            }
            echo '</ul>';
        } else { // STATE 3: submission successful

            // insert the record and inform user

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoFilePath)) {
                die("Error moving the uploaded file. Action aborted.");
            }

            $sql = sprintf(
                "INSERT INTO auctions VALUES (NULL, '%s', '%s', '%s', '%s', '%s', NULL, NULL)",
                mysqli_real_escape_string($link, $itemDesc),
                mysqli_real_escape_string($link, $photoFilePath),
                mysqli_real_escape_string($link, $sellerName),
                mysqli_real_escape_string($link, $sellerEmail),
                mysqli_real_escape_string($link, $startBid)
            );
            if (!mysqli_query($link, $sql)) {
                die("Fatal error: failed to execute SQL query: " . mysqli_error($link));
            }
            echo "<p>Item posted succesfully.</p>";
            echo '<p><a href="index.html">Click to continue</a></p>';
        }
    } else {
        displayForm();
    }








    ?>

</body>

</html>