if (document.getElementById('login')) {
    document.getElementById('login').addEventListener('click', function () {
        location.href = 'login.php';
    });
}

if (document.getElementById('register')) {
    document.getElementById('register').addEventListener('click', function () {
        location.href = 'new_account.php';
    });
}

if (document.getElementById('logout')) {
    document.getElementById('logout').addEventListener('click', function () {
        location.href = 'logout.php';
    });
}

document.getElementById('cart').addEventListener('click', function () {
    location.href = 'viewCart.php';
});

if (document.getElementById('back')) {
    document.getElementById('back').addEventListener('click', function () {
        location.href = 'homepage.php';
    })
}