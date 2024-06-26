<?php
    setcookie("developerId", "", time() - 3600, "/developer");
    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $redirect = $_GET["redirect"] ?? "/developer/sign-in";
        header ("Location: $redirect"); 
    } else {
        header ("Location: /developer/sign-in");
    }
