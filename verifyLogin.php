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

    function validate($db_conn, $userid, $pw): bool
    {
        $query = "SELECT * FROM Login WHERE Userid = '$userid' and Pw = '$pw';";
        $login_record = mysqli_query($db_conn, $query)
            or die("Query Error!".mysqli_error($db_conn));
        if (mysqli_num_rows($login_record) > 0) {
            mysqli_free_result($login_record);
            return true;
        }
        mysqli_free_result($login_record);
        return false;
    }

    function getBookIdList($db_conn): array
    {
        $query = "SELECT DISTINCT BookId FROM Book;";
        $results = mysqli_query($db_conn, $query)
            or die("Query Error!".mysqli_error($db_conn));
        if (mysqli_num_rows($results) > 0) {
            $bookIdList = mysqli_fetch_all($results);
            mysqli_free_result($results);
            return $bookIdList;
        }
        mysqli_free_result($results);
        return array();
    }

    function deleteCart($db_conn, $userid) {
        $query = "DELETE FROM Cart WHERE UserId = '$userid';";
        if (!mysqli_query($db_conn, $query)) {
            die("Query Error!".mysqli_error($db_conn));
        }
    }

    function updateCartDB($db_conn, $userid, $bookId, $qty) {
        $query = "INSERT INTO Cart (UserId, BookId, qty) VALUES ('$userid', '$bookId', '$qty');";
        if (!mysqli_query($db_conn, $query)) {
            die("Query Error!".mysqli_error($db_conn));
        }
    }

    function getCart($db_conn, $userid): array {
        $query = "SELECT * FROM Cart WHERE UserId = '$userid';";
        $results = mysqli_query($db_conn, $query)
            or die("Query Error!".mysqli_error($db_conn));
        if (mysqli_num_rows($results) > 0) {
            $Cart = mysqli_fetch_all($results);
            mysqli_free_result($results);
            return $Cart;
        }
        mysqli_free_result($results);
        return array();
    }

    $db_conn = mysqli_connect("sophia.cs.hku.hk", "skso", "063405", "skso")
    or die("Connection Error!".mysqli_connect_error());

    if (isset($_SESSION['userid'])) {
        header('location: homepage.php');
    }
    else {
        if (validate($db_conn, $_POST['userid'], $_POST['pw'])) {
            $_SESSION['userid'] = $_POST['userid'];
            $userid = $_SESSION['userid'];
            if (isset($_SESSION['cartCount']) && $_SESSION['cartCount'] > 0) {
                # replace current Cart data to database
                # delete all Cart record from database
                deleteCart($db_conn, $userid);

                # get $bookIdList
                $bookIdList = getBookIdList($db_conn);
                foreach ($bookIdList as $book) {
                    $bookId = $book[0];
                    # current book is in Cart
                    if (isset($_SESSION["$bookId"]) && $_SESSION["$bookId"] > 0) {
                        updateCartDB($db_conn, $userid, $bookId, $_SESSION["$bookId"]);
                    }
                }
            }
            else {
                # check and retrieve Cart data from database
                $Cart = getCart($db_conn, $userid);
                foreach ($Cart as $item) {
                    $bookId = $item[1];
                    $qty = $item[2];
                    $_SESSION["cartCount"] += $qty;
                    $_SESSION["$bookId"] = $qty;
                }
            }
             header('location: homepage.php');
        }
        else {
            $msg = "<h1>Invalid login, please login again.</h1>
            <h3>Returning to login page in 3 seconds.</h3>";
            echo $msg;
            header('Refresh: 3; URL=login.php');
        }
    }
    mysqli_close($db_conn);

?>
</body>
</html>
