<?php
session_start();

# database connection
$db_conn = mysqli_connect("sophia.cs.hku.hk", "skso", "063405", "skso")
or die("Connection Error! ".mysqli_connect_error());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gary Bookstore - Shopping Cart</title>
    <link rel="icon" href="https://www.freeiconspng.com/thumbs/cart-icon/basket-cart-icon-27.png">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/viewCart.css">
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

<div id="bottom-div" class="flex col-dir">
    <h2>My Shopping Cart</h2>
    <?php
    $order = array();
    $totalPrice = 0;
    if ($_SESSION['cartCount'] == 0) {
        echo "<p>No item in the shopping cart.</p>";
    }
    else {
        $query = "SELECT BookId, BookName, Price FROM Book;";
        if ($results = mysqli_query($db_conn, $query)) {
            if (mysqli_num_rows($results) > 0) {
                while ($book = mysqli_fetch_array($results)) {
                    $bookId = $book['BookId'];
                    $bookName = $book['BookName'];
                    $Price = $book['Price'];

                    if (isset($_SESSION["$bookId"])) {
                        $orderQty = $_SESSION["$bookId"];
                        $totalPrice += $Price * $orderQty;
                        $order["$bookId"] = array("bookId" => $bookId, "bookName" => $bookName, "qty" => $orderQty, "price" => $Price);

                        $str = "<div id='item-$bookId' class='order-item flex row-dir flex-space-around align-center'>
                                <span id='item-title-$bookId' class='item-title'>Book Name: $bookName</span>
                                <span id='item-qty-$bookId' class='item-qty'>Quantity: $orderQty</span>
                                <span id='item-price-$bookId' class='item-price'>Price per unit: $Price</span>
                                <form id='delete-itme-form' action='delete_cart.php' method='POST'>
                                    <input name='bookId' value='$bookId' hidden>
                                    <input name='qty' value='$orderQty' hidden>
                                    <button type='submit' id='item-delete-$bookId' name='item-delete-$bookId' class='mid-size'>Delete</button>
                                </form>
                                </div>";
                        echo $str;
                    }
                }
                echo "<span id='total-price'>Total Price: $ $totalPrice</span>";
            }
            else {
                echo "<p>No item in the shopping cart.</p>";
            }

            # free $results
            mysqli_free_result($results);
            mysqli_close($db_conn);
        }
        else {
            echo "<p>Connection Error! ". mysqli_connect_error() . "</p>";
        }
    }
    ?>
    <form id="bottom-fx" class="flex flex-start row-dir" action="checkout.php" method="POST">
        <?php
            foreach ($order as $item) {
                $bookId = $item["bookId"];
                $bookName = $item["bookName"];
                $qty = $item["qty"];
                $price = $item["price"];
                echo "<input name='bookName-$bookId' value='$bookName' hidden>";
                echo "<input name='qty-$bookId' value='$qty' hidden>";
                echo "<input name='price-$bookId' value='$price' hidden>";
            }
            echo "<input name='totalPrice' value='$totalPrice' hidden>";
        ?>
        <button type="button" id="back" class="mid-size almond-color">Back</button>
        <button type="submit" id="checkout" name="checkout" class="mid-size almond-color" <?php
        if ($_SESSION['cartCount'] == 0) { echo "style='display: none;'"; };
        ?>>Checkout</button>
    </form>

</div>

<!-- JavaScript -->
<script src="js/nav-fx.js"></script>
</body>
</html>
