<?php
session_start();

if (isset($_POST["userid"]) && isset($_POST["pw"])) {
# database connection
    $db_conn = mysqli_connect("sophia.cs.hku.hk", "skso", "063405", "skso")
    or die("Connection Error! ".mysqli_connect_error());

    $userid = $_POST["userid"];
    $pw = $_POST["pw"];
    $query = "INSERT INTO Login (Userid, Pw) VALUES ('$userid', '$pw')";
    if (!mysqli_query($db_conn, $query)) {
        die("Query Error!".mysqli_error($db_conn));
    }
    $_SESSION["userid"] = $userid;

# disconnect database
    mysqli_close($db_conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gary Bookstore - Invoice</title>
    <link rel="icon" href="https://lh3.googleusercontent.com/proxy/Cz1rbXJZXFWeCwzMsHG_AO0E74E2llJD8M3I6UTYbjf8xavVqtdYK5Z4MIIIQszaU9n4fVRIHzr9wG6Qttpk4U_ECuEgYoNY200xE3y-Kg">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/checkout.css">
    <!-- External CSS (icon and font) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
</head>
<body>
    <h1 style="text-align: left">Invoice Page</h1>
    <div id="personal-info" class="flex col-dir flex-start">
        <?php
        $fullName = $_POST["full-name"];
        $company = ($_POST["company-name"] != "")?$_POST["company-name"]:"NA";
        $addr_1 = $_POST["addr-1"];
        $addr_2 = ($_POST["addr-2"] != "")?$_POST["addr-2"]:"NA";
        $city = $_POST["city"];
        $region = ($_POST["region"] != "")?$_POST["region"]:"NA";
        $country = $_POST["country"];
        $zip_code = $_POST["zip-code"];
        echo "<span>Full Name: $fullName</span>";
        echo "<span>Company: $company</span>";
        echo "<span>Address Line 1: $addr_1</span>";
        echo "<span>Address Line 2: $addr_2</span>";
        echo "<span>City: $city</span>";
        echo "<span>Region: $region</span>";
        echo "<span>Country: $country</span>";
        echo "<span>Postcode: $zip_code</span>";
        ?>
    </div>
    <hr>
    <div id="product-details" class="flex col-dir flex-start">
        <?php
        $i = 1;
        while (isset($_POST["item-$i"])) {
            $item = $_POST["item-$i"];
            $price = $_POST["price-$i"];
            echo "<div class='flex row-dir flex-start'>
                    <span class='book-details'>$item</span>
                    <span class='book-price'>$price</span>
                  </div>";
            $i += 1;
        }
        ?>
    </div>
    <h4>Total Price: HK$ <?php echo $_POST["totalPrice"];?></h4>
    <hr>
    <div id="confirm" class="flex col-dir flex-center align-center">
        <h5 style="text-align: center;">Thanks for ordering. Your books will be delivered within 7 working days.</h5>
        <button type="button" id="ok" class="mid-size almond-color">OK</button>
    </div>

    <!-- JavaScript -->
    <script src="js/invoice.js"></script>
</body>
</html>