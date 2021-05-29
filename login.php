<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gary Bookstore - LOGIN</title>
    <link rel="icon" href="https://img.icons8.com/cotton/2x/login-rounded-right.png">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
    <!-- External CSS (icon and font) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
</head>
<body>
    <!-- Header -->
    <header class="top-bar flex col-dir">
        <form action="homepage.php" method="GET" name="search-form" id="search-form" class="flex row-dir align-center">
            <input type="search" name="keywords" id="keywords" maxlength="255" placeholder="Keyword(s)">
            <button type="submit" name="action" id="action" value="search" class="small-size">Search</button>
        </form>
        <div id="nav-fx" class="flex flex-end row-dir">
            <?php
            if (!isset($_SESSION['userid'])) {
                $login_btn = "<button type='button' name='login' id='login' class='mid-size'>Sign In</button>";
                $register_btn = "<button type='button' name='register' id='register' class='mid-size'>Register</button>";
                echo $login_btn;
                echo $register_btn;
            } else {
                $logout_btn = "<button type='button' name='logout' id='logout' class='mid-size'>Logout</button>";
                echo $logout_btn;
            }
            ?>
            <!-- Cart -->
            <?php
            if (!isset($_SESSION['cartCount'])) {
                $_SESSION['cartCount'] = 0;
            };
            ?>
            <button type="button" name="cart" id="cart" class="mid-size">Cart (<?php echo $_SESSION['cartCount'];?>)</button>
        </div>
    </header>

    <section class="main-fx">
        <h1>Gary Bookstore - LOGIN</h1>
            <form action="verifyLogin.php" method="POST" name="login-form" id="login-form" onsubmit="return validate();">
                <div id="login" class="flex flex-center col-dir">
                    <span class="login-field">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <input type="text" name="userid" id="userid" maxlength="12" placeholder="Username">
                    </span>
                    <br>
                    <span class="login-field">
                        <i class="fa fa-key" aria-hidden="true"></i>
                        <input type="password" name="pw" id="pw" maxlength="8" placeholder="Password">
                    </span>
                    <div id="form-button" class="flex flex-center row-dir">
                        <button type="submit" name="submit" id="submit" class="mid-size">SUBMIT</button>
                        <button type="button" name="create" id="create" class="mid-size">CREATE</button>
                    </div>
                </div>
            </form>
    </section>
    <!-- JavaScript -->
    <script src="js/login.js"></script>
    <script src="js/nav-fx.js"></script>
</body>
</html>