<?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['requestId'];
        $status = $_POST['status'];

        $requests = json_decode(file_get_contents('../../database/requests.json'), true);
        foreach ($requests as $key => $request) {
            if ($request['id'] == $id) {
                $requests[$key]['status'] = $status;
                if ($status == 'finished') {
                    $games = json_decode(file_get_contents('../../database/games.json'), true);
                    foreach ($request['gamesId'] as $gameId) {
                        foreach ($games as $key => $game) {
                            if ($game['id'] == $gameId) {
                                $games[$key]['sells']++;
                                break;
                            }
                        }
                    }

                    file_put_contents('../../database/games.json', json_encode($games));
                }
                break;
            }
        }

        file_put_contents('../../database/requests.json', json_encode($requests));

        header('Location: /profile/requests/details/?id=' . $id);
    }