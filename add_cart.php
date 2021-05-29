<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php

# database connection
$db_conn = mysqli_connect("sophia.cs.hku.hk", "skso", "063405", "skso")
or die("Connection Error! ".mysqli_connect_error());

$bookId = $_POST['order'];
$qty = (int)$_POST['qty'];

# if user is logged in -> add to database
if (isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid'];

    $query = "SELECT qty FROM Cart WHERE UserId = '$userId' AND BookId = '$bookId';";
    if ($results = mysqli_query($db_conn, $query)) {
        if (mysqli_num_rows($results) > 0) {
            $val = mysqli_fetch_array($results);
            $newQty = $qty + (int)$val['qty'];
            $query = "UPDATE Cart SET qty = $newQty WHERE UserId = '$userId' AND BookId = '$bookId';";
        }
        else {
            $query = "INSERT INTO Cart (UserId, BookId, qty) VALUES ('$userId', '$bookId', '$qty');";
        }

        # update session cookie
        if (mysqli_query($db_conn, $query)) {
            $_SESSION["cartCount"] += $qty;
            if (isset($_SESSION["$bookId"])) {
                $_SESSION["$bookId"] += $qty;
            }
            else {
                $_SESSION["$bookId"] = $qty;
            }
        }
        else {
            echo "<p>Connection Error! ". mysqli_connect_error() . "</p>";
        }

        # free $results
        mysqli_free_result($results);
        mysqli_close($db_conn);
    }
    else {
        echo "<p>Connection Error! ". mysqli_connect_error() . "</p>";
    }
}
# if user has not log in -> add to session cookie
else {
    if (isset($_SESSION["$bookId"])) {
        $_SESSION["$bookId"] += $qty;
    }
    else {
        $_SESSION["$bookId"] = $qty;
    }
    $_SESSION["cartCount"] += $qty;
}
header("location: homepage.php?action=book&bookId=$bookId");
?>
</body>
</html>