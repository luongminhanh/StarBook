<?php
if (isset($_GET['option'])) {
    switch ($_GET['option']) {
        case "home":
            include "views/home.php";
            break;
        case "home":
            include "views/home.php";
            break;
        case "feedback":
            include "views/feedback.php";
            break;
        case "signin":
            include "views/signin.php";
            break;
        case "register":
            include "views/register.php";
            break;
        case "cart":
            include "views/cart.php";
            break;
        case "logout":
            unset($_SESSION['member']);
            header("location: ?option=home");
            break;
    }
} else {
    include "views/home.php";
}
