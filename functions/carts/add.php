<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $userId = $_COOKIE["userId"];
        $users = json_decode(file_get_contents("../../database/users.json"), true);
        $user = null;
        foreach($users as $currentUser) {
            if($currentUser['id'] == $userId) {
                $user = $currentUser;
                break;
            }
        }

        if($user === null) {
            header("location: /auth/sign-in");
            return;
        }

        $games = json_decode(file_get_contents("../../database/games.json"), true);
        $gameId = $_POST['gameId'];
        $game = null;
        foreach($games as $currentGame) {
            if($currentGame['id'] == $gameId) {
                $game = $currentGame;
                break;
            }
        }

        if($game === null) {
            header("location: /erro/404.php?erro=Jogo%20n%C3%A3o%20encontrado&id=" . $gameId);
            return;
        }

        $carts = json_decode(file_get_contents("../../database/carts.json"), true);

        $cart = [
            "userId" => $userId,
            "gameId" => $gameId
        ];

        $carts[] = $cart;
        file_put_contents("../../database/carts.json", json_encode($carts));

        header("location: /game/?id=" . $gameId);
    }