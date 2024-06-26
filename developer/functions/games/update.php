<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["gameId"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $images = explode(' ', $_POST["images"]);
        array_shift($images);
        $price = floatval($_POST["price"]);
        $cover = $_POST["cover"];
        $category = $_POST["category"];

        $games = json_decode(file_get_contents("../../../database/games.json"), true);

        foreach ($games as $index => $game) {
            if ($game["id"] == $id && $game["developerId"] == $_COOKIE["developerId"]) {
                $game["name"] = $name;
                $game["description"] = $description;
                $game["images"] = $images;
                $game["price"] = $price;
                $game["cover"] = $cover;
                $game["category"] = $category;

                $games[$index] = $game;
                break;
            }
        }

        file_put_contents("../../../database/games.json", json_encode($games, JSON_PRETTY_PRINT));

        header("location: /developer/game/?id=" . $id);
    }