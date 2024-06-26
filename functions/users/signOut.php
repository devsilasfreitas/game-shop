<?php
    setcookie("userId", "", time() - 3600, "/");
    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $redirect = $_GET["redirect"] ?? "/";
        header ("Location: $redirect"); 
    } else {
        header ("Location: /");
    }
