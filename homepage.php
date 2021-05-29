<?php
session_start();

# database connection
$db_conn = mysqli_connect("sophia.cs.hku.hk", "skso", "063405", "skso")
    or die("Connection Error! ".mysqli_connect_error());

if (isset($_GET["action"]) && $_GET["action"] == "resetCart") {
    $userId = $_SESSION['userid'];
    $query = "DELETE FROM Cart WHERE UserId = '$userId';";
    if (!mysqli_query($db_conn, $query)) {
        die("Connection Error! ".mysqli_connect_error());
    }

    # delete session data
    $_SESSION['cartCount'] = 0;

    # unset all book data
    $query = "SELECT DISTINCT BookId FROM Book;";
    $results = mysqli_query($db_conn, $query);
    if (mysqli_num_rows($results) > 0) {
        while ($book = mysqli_fetch_array($results)) {
            $bookId = $book["BookId"];
            unset($_SESSION["$bookId"]);
        }
    }
    mysqli_free_result($results);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gary Bookstore - Homepage</title>
  <link rel="icon" href="https://cdn0.iconfinder.com/data/icons/set-app-incredibles/24/Home-01-512.png">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/homepage.css">
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

<div id="bottom-div" class="flex row-dir flex-start">
<!-- Left Panel -->
<?php
    if (!isset($_GET['action']) || $_GET['action'] != 'book') {
        echo "<section class='left-pane' class='flex col-dir'>
        <h4>Category</h4><br>";
        $query = "SELECT DISTINCT Category FROM Book;";
        if ($books = mysqli_query($db_conn, $query)) {
            if (mysqli_num_rows($books) > 0) {
                while ($book = mysqli_fetch_array($books)) {
                    $Category = $book['Category'];
                    echo "<a href='homepage.php?action=category&category=$Category'>$Category</a>";
                    echo "<br>";
                }
            }
            mysqli_free_result($books);
        }
        else {
            echo "<p>Connection Error! ". mysqli_connect_error() . "</p>";
        }
        echo "</section>";
    }
    ?>

<!-- Main Panel -->
<section class="main-pane">
    <div id="main-title">
        <a href="homepage.php">Home</a>
        <?php

        $query = "SELECT * FROM Book;";
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'category') {
                $category = $_GET['category'];
                $query = "SELECT * FROM Book WHERE Category = \"$category\";";
            }
            else if ($_GET['action'] == 'book') {
                $BookId = $_GET['bookId'];
                $query = "SELECT * FROM Book WHERE BookId = '$BookId'";
            }
            else if ($_GET['action'] == 'search') {
                $str = explode(" ", $_GET['keywords']);
                $query = "SELECT * FROM Book WHERE ";
                foreach ($str as $searchKey) {
                    $query .= "BookName LIKE '%$searchKey%' OR Author LIKE '%$searchKey%' OR ";
                }
                $query = substr($query, 0, -4);
                $query .= ";";
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'category') {
            $category = $_GET['category'];
            $current_a = "<a href='homepage.php?action=category&category=$category'>$category</a>";
            echo " > " . $current_a;
            echo "<br>";
            echo "<h2 id='main-heading'>All $category</h2>";
        }
        else if (isset($_GET['action']) && $_GET['action'] == 'book') {
            $bookId = $_GET['bookId'];
            $bookTitle = "";
            if ($books = mysqli_query($db_conn, $query)) {
                if (mysqli_num_rows($books) > 0) {
                    $book = mysqli_fetch_array($books);
                    $bookTitle = $book['BookName'];
                }
            }
            else {
                echo "<p>Connection Error! ". mysqli_connect_error() . "</p>";
            }
            $current_a = "<a href='homepage.php?action=book&bookId=$bookId'>$bookTitle</a>";
            echo " > " . $current_a;
            echo "<br>";
        } else if (isset($_GET['action']) && $_GET['action'] == 'search') {
            echo "<br>";
            echo "<h2 id='main-heading'>Search Results</h2>";
        } else {
            echo "<br>";
            echo "<h2 id='main-heading'>All Books</h2>";
        }
        ?>
    </div>
    <div id="sort-fx" class="flex row-dir flex-end">
        <?php
        if (!isset($_GET['action']) || $_GET['action'] != 'book') {
            echo "<button type='button' name='sort' id='sort' class='mid-size'>Sort by Price (Highest)</button>";
        }
        ?>
    </div>
<?php
    if (isset($_GET['action']) && $_GET['action'] == 'book') {
        echo "<div id='book-details' class='flex col-dir align-start'>";
    }
    else {
        echo "<div id='book-list' class='flex row-dir align-start'>";
    }

    if ($books = mysqli_query($db_conn, $query)) {
        if (mysqli_num_rows($books) > 0) {
            $BookCount = 0;
            while ($book = mysqli_fetch_array($books)) {
                $BookCount++;
                $BookId = $book['BookId'];
                $BookName = $book['BookName'];
                $Publisher = $book['Publisher'];
                $Category = $book['Category'];
                $Lang = $book['Lang'];
                $Author = $book['Author'];
                $Description = $book['Description'];
                $Price = $book['Price'];
                $Published = $book['Published'];
                $NewArrival = $book['NewArrival'];
                $BookImage = $book['BookImage'];

                if (isset($_GET['action']) && $_GET['action'] == 'book') {
                    $str = "<h2>$BookName</h2>
                        <img src='book_image/$BookImage' class='book-img-large' alt='$BookName'>
                        <span>Author: $Author</span>
                        <span>Published: $Published</span>
                        <span>Publisher: $Publisher</span>
                        <span>Category: $Category</span>
                        <span>Language: $Lang</span>
                        <span>Description: $Description</span>
                        <span>Price: $Price</span><br>
                        <form id='add-book-form' class='flex flex-start align-center' action='add_cart.php' method='POST'>
                            <label for='qty'>Order: </label>
                            <input name='qty' id='qty' type='number' class='small-input' placeholder='Quantity' required min='1' value='1'>
                            <button type='submit' name='order' id='order' class='mid-size almond-color' value='$BookId'>Add to Cart</button>                                    
                        </form>";
                }
                else {
                    $str = "<div id=\"book-$BookCount\" class=\"book flex col-dir flex-start\">
                    <span><a href=\"homepage.php?action=book&bookId=$BookId\">$BookName</a></span>
                    <img src=\"book_image/$BookImage\" class=\"book-img\" alt=\"$BookName\"> ";
                    if ($NewArrival == "1") {
                        $str .= "<span class=\"new-arrival\">NEW ARRIVAL!</span>";
                    }
                    $str .= "<span id=\"author-$BookCount\" name='author'>Author: $Author</span>
                    <span id=\"publisher-$BookCount\" name='publisher'>Publisher: $Publisher</span>
                    <span id=\"price-$BookCount\" name='price'>Price: $Price</span>
                    </div>";
                }
                echo $str;
            }
        }
        else {
            echo "<h3>Cannot found any book with such criteria.</h3>";
        }

        #free $books
        mysqli_free_result($books);
    }
    else {
        echo "<p>Connection Error! ". mysqli_connect_error() . "</p>";
    }

    # disconnect database
    mysqli_close($db_conn);
?>
    </div>
</section>
</div>

<!-- JavaScript -->
<script src="js/nav-fx.js"></script>
<script src="js/homepage.js"></script>
</body>
</html>