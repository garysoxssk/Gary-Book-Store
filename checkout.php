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
    <title>Gary Bookstore - Checkout</title>
    <link rel="icon" href="https://www.freeiconspng.com/thumbs/cart-icon/basket-cart-icon-27.png">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/checkout.css">
    <!-- External CSS (icon and font) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <!-- External jQuery(3.5.1) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<section id="order-confirm">
<?php
    if (!isset($_SESSION['userid'])) {
        echo "<div class='flex col-dir flex-start'>
                  <div class='flex row-dir flex-space-around'>
                    <span class='col-1 font-bold'>I'm a new customer</span>
                    <span class='col-2'></span>
                    <span class='col-3 font-bold'>I'm already a customer</span>
                  </div>
                  <div class='flex row-dir flex-space-around'>
                    <span class='col-1'>Please checkout Below</span>
                    <span class='col-2'> or </span>
                    <span class='col-3'><a href='login.php'>Sign In</a></span>
                  </div>
              </div>";
        echo "<hr>";
        echo "<form action='invoice.php' method='POST' name='invoice-form' id='invoice-form' class='flex col-dir flex-start'>
                <div id='create' class='flex flex-center col-dir'>
                  <span class='create-field'>
                      <i class='fa fa-user' aria-hidden='true'></i>
                      <input type='text' name='userid' id='userid' maxlength='12' placeholder='Desired Username'>
                  </span>
                  <span id='warning' class='warning' style='display: none;'>Username Duplicated!</span>
                  <span class='create-field'>
                    <i class='fa fa-key' aria-hidden='true'></i>
                    <input type='password' name='pw' id='pw' maxlength='8' placeholder='Desired Password'>
                  </span>
                </div>
              <hr>";
    }
    else {
        echo "<form action='invoice.php' method='POST' name='invoice-form' id='invoice-form' class='flex col-dir flex-start'>";
    }
?>
        <h2>Delivery Address: </h2>
        <label for="full-name">Full Name</label>
        <input type="text" name="full-name" id="full-name" class="mid-input" placeholder="Required">

        <label for="company-name">Company Name</label>
        <input type="text" name="company-name" id="company-name" class="mid-input">

        <label for="addr-1">Address Line 1</label>
        <input type="text" name="addr-1" id="addr-1" class="mid-input" placeholder="Required">

        <label for="addr-2">Address Line 2</label>
        <input type="text" name="addr-2" id="addr-2" class="mid-input">

        <label for="city">City</label>
        <input type="text" name="city" id="city" class="mid-input" placeholder="Required">

        <label for="region">Region / State / District</label>
        <input type="text" name="region" id="region" class="mid-input">

        <label for="country">Country</label>
        <input type="text" name="country" id="country" class="mid-input" placeholder="Required">

        <label for="zip-code">Postcode / Zip Code</label>
        <input type="text" name="zip-code" id="zip-code" class="mid-input" placeholder="Required">

        <hr>

        <p>Your order: (<a href="viewCart.php">change</a>)</p>
        <h4>Free Standard Shipping</h4>
        <div class="flex col-dir flex-start"><?php
            $query = "SELECT DISTINCT BookId FROM Book;";
            if ($results = mysqli_query($db_conn, $query)) {
                if (mysqli_num_rows($results) > 0) {
                    $i = 1;
                    while ($book = mysqli_fetch_array($results)) {
                        $bookId = $book['BookId'];
                        if (isset($_POST["bookName-$bookId"])) {
                            $bookName = $_POST["bookName-$bookId"];
                            $qty = $_POST["qty-$bookId"];
                            $price = $_POST["price-$bookId"];
                            echo "<div class='flex row-dir flex-start'>
                                    <span class='book-details'>$qty x $bookName</span>
                                    <span class='book-price'>HK$ $price</span>
                                  </div>
                                  <input name='item-$i' value='$qty x $bookName' hidden>
                                  <input name='price-$i' value='HK$ $price' hidden>";
                            $i += 1;
                        }
                    }
                }
            }
            else {
                echo "Connection error" . mysqli_connect_error();
            }

            # free $results
            mysqli_free_result($results);
            mysqli_close($db_conn);
        ?></div>
        <h4>Total Price: HK$ <?php echo $_POST["totalPrice"];?></h4>
        <?php $totalPrice = $_POST["totalPrice"]; echo "<input name='totalPrice' value='$totalPrice' hidden>";?>
        <button type="submit" name="confirm" id="confirm" class="mid-size almond-color">Confirm</button>
    </form>
</section>

<!-- JavaScript -->
<script src="js/checkout.js"></script>
</body>
</html>