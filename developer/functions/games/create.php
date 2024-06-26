<?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!isset($_COOKIE['developerId'])) {
            header('location: /developer/sign-in');
            return;
        }

        $name = $_POST['name'];
        $description = $_POST['description'];
        $images = explode(' ', $_POST['images']);
        array_shift($images);
        $price = floatval($_POST['price']);
        $developerId = $_COOKIE['developerId'];
        $cover = $_POST['cover'];
        $category = $_POST['category'];

        $game = [
            'id' => uniqid(),
            'name' => $name,
            'description' => $description,
            'images' => $images,
            'price' => $price,
            'developerId' => $developerId,
            'sells' => 0,
            'cover' => $cover,
            'category' => $category
        ];

        $games = json_decode(file_get_contents('../../../database/games.json'), true);

        $games[] = $game;

        file_put_contents('../../../database/games.json', json_encode($games, JSON_PRETTY_PRINT));

        header('location: /developer/game/?id=' . $game['id']);
    }