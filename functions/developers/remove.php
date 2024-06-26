<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_COOKIE["userId"];
        $password = $_POST["password"];

        $users = json_decode(file_get_contents("../../database/users.json"), true);


        foreach ($users as $index => $currentUser) {
            if ($currentUser["id"] == $userId) {
                if ($currentUser["password"] == $password) {
                    unset($users[$index]);
                    file_put_contents("../../database/users.json", json_encode($users));
                    $carts = json_decode(file_get_contents("../../database/carts.json"), true);
                    foreach ($carts as $index => $currentCart) {
                        if ($currentCart["userId"] == $userId) {
                            unset($carts[$index]);
                        }
                    }
                    file_put_contents("../../database/carts.json", json_encode($carts));
                    $favorites = json_decode(file_get_contents("../../database/favorites.json"), true);
                    foreach ($favorites as $index => $currentFavorite) {
                        if ($currentFavorite["userId"] == $userId) {
                            unset($favorites[$index]);
                        }
                    }
                    file_put_contents("../../database/favorites.json", json_encode($favorites));
                    $requests = json_decode(file_get_contents("../../database/requests.json"), true);
                    foreach ($requests as $index => $currentRequest) {
                        if ($currentRequest["userId"] == $userId && array_search($currentRequest["status"], ["pending", "canceled"]) !== false) {
                            unset($requests[$index]);
                        }
                    }
                    file_put_contents("../../database/requests.json", json_encode($requests));
                    setcookie("userId", "", time() - 3600, "/");
                    header("location: /");
                    return;
                } else {
                    header("location: /profile/?error=Senha incorreta#remove-account");
                    return;
                }
            }
        }

        header("location: /auth/sign-in");
    }