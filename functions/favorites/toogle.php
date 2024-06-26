<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_COOKIE['userId'])) {
            header('Location: /auth/sign-in');
            return;
        }
        if (!isset($_GET['gameId'])) {
            header('Location: /erro/404.php?erro=Jogo%20n%C3%A3o%20encontrado&id=' . $_GET['gameId']);
            return;
        }
        $gameId = $_GET['gameId'];

        $userId = $_COOKIE['userId'];

        $users = json_decode(file_get_contents('../../database/users.json'), true);

        $user = null;
        foreach ($users as $u) {
            if ($u['id'] == $userId) {
                $user = $u;
                break;
            }
        }

        if ($user == null) {
            setcookie('userId', '', time() - 3600, '/');
            header('Location: /auth/sign-in');
            return;
        }

        $games = json_decode(file_get_contents('../../database/games.json'), true);
        $game = null;
        foreach ($games as $g) {
            if ($g['id'] == $gameId) {
                $game = $g;
                break;
            }
        }

        if ($game == null) {
            header('Location: /erro/404.php?erro=Jogo%20n%C3%A3o%20encontrado&id=' . $gameId);
            return;
        }

        $favorites = json_decode(file_get_contents('../../database/favorites.json'), true);
        $favorite = null;

        foreach ($favorites as $index => $f) {
            if ($f['userId'] == $userId && $f['gameId'] == $gameId) {
                $favorite = $f;
                unset($favorites[$index]);
                break;
            }
        }

        if ($favorite == null) {
            $favorites[] = [
                'userId' => $userId,
                'gameId' => $gameId
            ];
        }
        file_put_contents('../../database/favorites.json', json_encode($favorites));

        header('Location: /game/?id=' . $gameId);
    }