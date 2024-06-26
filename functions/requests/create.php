<?php
    $userId = $_COOKIE["userId"];

    $cart = json_decode(file_get_contents("../../database/carts.json"), true);
    $gamesArray = json_decode(file_get_contents("../../database/games.json"), true);
    $games = [];

    foreach($cart as $currentCart) {
        if($currentCart["userId"] == $userId) {
            $games[] = $currentCart["gameId"];
        }
    }

    if (count($games) == 0) {
        header("location: /profile/my-cart");
        return;
    }

    $status = "pending";

    $price = 0;
    foreach($games as $currentGame) {
        $game = null;
        foreach($gamesArray as $currentGameArray) {
            if($currentGame == $currentGameArray["id"]) {
                $game = $currentGameArray;
                break;
            }
        }
        $price += $game["price"];
    }

    $request = [
        "id" => uniqid(),
        "userId" => $userId,
        "gamesId" => $games,
        "status" => $status,
        "price" => $price
    ];

    $requests = json_decode(file_get_contents("../../database/requests.json"), true);
    $requests[] = $request;
    file_put_contents("../../database/requests.json", json_encode($requests));

    $carts = json_decode(file_get_contents("../../database/carts.json"), true);
    foreach($carts as $index => $currentCart) {
        if($currentCart["userId"] == $userId) {
            unset($carts[$index]);
        }
    }
    file_put_contents("../../database/carts.json", json_encode($carts));

    header("location: /profile/requests/details/?id=" . $request["id"]);