<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gary Bookstore - Logout</title>
    <link rel="icon" href="https://www.iconpacks.net/icons/2/free-exit-logout-icon-2857-thumb.png">
    <link rel="stylesheet" href="css/global.css">
    <!-- External CSS (icon and font) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
</head>
<body>

<?php
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
session_unset();
session_destroy();

echo "<h2>Logging out</h2>";

header('Refresh: 3; URL=homepage.php');
?>

</body>
</html>