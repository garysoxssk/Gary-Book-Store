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

$bookId = $_POST['bookId'];

# if user has logged in, delete DB data
if (isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid'];
    $query = "DELETE FROM Cart WHERE UserId = '$userId' AND BookId = '$bookId';";
    if (!mysqli_query($db_conn, $query)) {
        die("Connection Error! ".mysqli_connect_error());
    }
}

# delete session data
$_SESSION['cartCount'] -= $_POST['qty'];
unset($_SESSION["$bookId"]);

mysqli_close($db_conn);
header("location: viewCart.php");
?>
</body>
</html>
