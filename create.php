<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gary Bookstore - Homepage</title>
    <link rel="icon" href="https://img.icons8.com/cotton/2x/login-rounded-right.png">
    <link rel="stylesheet" href="css/global.css">
    <!-- External CSS (icon and font) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
</head>
<body>

<?php

function checkoutValidate($userid):bool {
    $db_conn = mysqli_connect("sophia.cs.hku.hk", "skso", "063405", "skso")
        or die("Connection Error!".mysqli_connect_error());
    $query = "SELECT * FROM Login WHERE Userid = '$userid'";
    $login_record = mysqli_query($db_conn, $query)
        or die("Query Error!".mysqli_error($db_conn));
    if (mysqli_num_rows($login_record) <= 0) {
        mysqli_free_result($login_record);
        mysqli_close($db_conn);
        return true;
    }
    mysqli_free_result($login_record);
    mysqli_close($db_conn);
    return false;
}

if (isset($_GET["action"]) && $_GET["action"] == "checkoutValidate"){
    if (isset($_GET["userid"])) {
        $userid = $_GET["userid"];
        if (checkoutValidate($userid)) {
            echo "VALID ACCOUNT";
        }
    }
}
else {
    if (isset($_SESSION['userid'])) {
        $msg = "<h1>Please logout first.</h1>
            <h3>Returning to our Homepage in 3 seconds.</h3>";
        echo $msg;
        header('Refresh: 3; URL=homepage.php');
    }
    else {
        if (validate($_POST['userid'], $_POST['pw'])) {
            $_SESSION['userid'] = $_POST['userid'];
            $msg = "<h1>Account created! Welcome " . $_POST['userid'] . "!</h1>
                    <h3>Returning to our Homepage in 3 seconds.</h3>";
            echo $msg;
            header('Refresh: 3; URL=homepage.php');
        }
        else {
            $msg = "<h1>Account already existed.</h1>
            <h3>Returning to login page in 3 seconds.</h3>";
            echo $msg;
            header('Refresh: 3; URL=login.php');
        }
    }
}

function validate($userid, $pw): bool {
    $db_conn = mysqli_connect("sophia.cs.hku.hk", "skso", "063405", "skso")
    or die("Connection Error!".mysqli_connect_error());
    $query = "SELECT * FROM Login WHERE Userid = '$userid'";
    $login_record = mysqli_query($db_conn, $query)
    or die("Query Error!".mysqli_error($db_conn));
    if (mysqli_num_rows($login_record) <= 0) {
        $query = "INSERT INTO Login (Userid, Pw) VALUES ('$userid', '$pw')";
        if (mysqli_query($db_conn, $query)) {
            mysqli_free_result($login_record);
            mysqli_close($db_conn);
            return true;
        }
    }
    mysqli_free_result($login_record);
    mysqli_close($db_conn);
    return false;
}
?>
</body>
</html>
