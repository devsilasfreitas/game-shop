<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["gameId"];
        $password = $_POST["password"];

        if (!isset($_COOKIE["developerId"])) {
            header("location: /developer/game/?id=" . $id . "&error=Sem permissão#delete");
            return;
        }

        $developers = json_decode(file_get_contents("../../../database/developers.json"), true);
        $developer = null;

        foreach ($developers as $index => $dev) {
            if ($dev["id"] == $_COOKIE["developerId"]) {
                $developer = $dev;
                break;
            }
        }

        if ($developer == null) {
            header("location: /developer/game?id=" . $id . "&error=Sem permissão#delete");
            return;
        }

        $games = json_decode(file_get_contents("../../../database/games.json"), true);

        foreach ($games as $index => $game) {
            if ($game["id"] == $id && $game["developerId"] == $_COOKIE["developerId"]) {
                if ($developer["password"] != $password) {
                    header("location: /developer/game?id=" . $id . "&error=Senha incorreta#delete");
                    return;
                }
                unset($games[$index]);
                break;
            }
        }

        $carts = json_decode(file_get_contents("../../../database/carts.json"), true);
        foreach ($carts as $index => $currentCart) {
            if ($currentCart["gameId"] == $id) {
                unset($carts[$index]);
            }
        }

        $favorites = json_decode(file_get_contents("../../../database/favorites.json"), true);
        foreach ($favorites as $index => $currentFavorite) {
            if ($currentFavorite["gameId"] == $id) {
                unset($favorites[$index]);
            }
        }

        $requests = json_decode(file_get_contents("../../../database/requests.json"), true);
        foreach ($requests as $index => $currentRequest) {
            if ($currentRequest["gameId"] == $id) {
                unset($requests[$index]);
            }
        }

        file_put_contents("../../../database/carts.json", json_encode($carts));
        file_put_contents("../../../database/favorites.json", json_encode($favorites));
        file_put_contents("../../../database/requests.json", json_encode($requests));

        file_put_contents("../../../database/games.json", json_encode($games));

        header("location: /developer/");
    }