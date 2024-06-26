<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $photoUrl = strlen($_POST["photoUrl"]) < 2 ? "https://i.ytimg.com/vi/3gDcG9qDfME/hqdefault.jpg" : $_POST["photoUrl"];
        $userId = $_COOKIE["userId"];

        $users = json_decode(file_get_contents("../../database/users.json"), true);

        foreach ($users as $index => $user) {
            if ($user["id"] == $userId) {
                $user["firstName"] = $firstName;
                $user["lastName"] = $lastName;
                $user["photoUrl"] = $photoUrl;
    
                $users[$index] = $user;
    
                file_put_contents("../../database/users.json", json_encode($users, JSON_PRETTY_PRINT)); 
            }
        }

        header("Location: /profile/");
    }