<?php
    if (!isset($_COOKIE["developerId"])) {
        header("location: /developer/sign-in");
        return;
    }
    $developers = json_decode(file_get_contents("../../database/developers.json"), true);
    $developer = null;
    foreach ($developers as $currentDeveloper) {
        if ($currentDeveloper["id"] == $_COOKIE["developerId"]) {
            $developer = $currentDeveloper;
            break;
        }
    }

    if ($developer === null) {
        setcookie("developerId", "", time() - 3600, "/developer");
        header("location: /developer/sign-in");
        return;
    }

    $games = json_decode(file_get_contents("../../database/games.json"), true);
    $game = null;

    foreach ($games as $g) {
        if ($g["id"] == $_GET["id"]) {
            $game = $g;
            break;
        }
    }

    if ($game === null) {
        header("location: /erro/404.php?erro=Jogo%20n%C3%A3o%20encontrado&id=" . $_GET["id"]);
        return;
    }
        $favorites = json_decode(file_get_contents("../../database/favorites.json"), true);
    
        $favoritesCount = 0;
        foreach ($favorites as $f) {
            if ($f["gameId"] == $game["id"]) {
                $favoritesCount++;
            }
        }


    $developersArray = json_decode(file_get_contents("../../database/developers.json"), true);

    $developer = null;

    foreach($developersArray as $d) {
        if ($game["developerId"] == $d["id"]) {
            $developer = $d;
        }
    }

    if ($developer === null) {
        header("location: /erro/404.php?erro=Desenvolvedor%20n%C3%A3o%20encontrado&id=" . $game["developerId"]);
        return;
    }

    $requests = json_decode(file_get_contents("../../database/requests.json"), true);

    $requestsCount = 0;

    foreach ($requests as $r) {
        if (array_search($game["id"], $r["gamesId"]) !== false && $r["status"] != "canceled") {
            $requestsCount++;
        }
    }

    $carts = json_decode(file_get_contents("../../database/carts.json"), true);

    $isInCarts = 0;
    foreach ($carts as $c) {
        if ( $c["gameId"] == $game["id"]) {
            $isInCarts++;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $game["name"] ?> | &lt;Game Shop /&gt;</title>
    <link rel="shortcut icon" href="/imgs/logo-dark.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-dark.css">
    <style>
        .images-container > img {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 420px;
            height: 250px;
            border-radius: 5px;
            object-fit: cover;
            cursor: pointer;
        }

        .images-container {
            white-space: nowrap;
            height: 250px;
            width: 100%;
            overflow-y: hidden;
            overflow-x: scroll;
        }

        .images-container img {
            margin-right: 10px;
        }

        .images-container::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body>
<header>
        <a href="/developer" class="logoContainer">
            <img src="/imgs/logo-dark.png" alt="Game Shop Logo" class="logo">
            <h1>&lt;Game Shop /&gt;</h1>
        </a>

        <div style="display: flex; align-items: center;gap: 15px">
            
            <div class="dropdown">
                <img src="<?= $developer["logoUrl"] ?>" alt="<?= $developer["name"] ?>"  class="avatar">
                <div class="dropdown-content">
                    <a href="/developer/profile">Perfil</a>
                    <hr>
                    <a href="/functions/developers/signOut.php">Sair da Conta</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="game-container">
            <img src="<?= $game["cover"] ?>" alt="<?= $game["name"] ?>" class="game-image">

            <div class="game-info">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <h1><?= $game["name"] ?></h1>
                </div>
                <a class="developerName" href="/developer/?id=<?= $developer["id"] ?>"><?= $developer["name"] ?></a>
                <p><?= $game["description"] ?></p>
                <div class="game-actions">
                    <a href="./update/?id=<?= $game["id"] ?>" class="button">Editar</a>
                    <a href="#delete" class="button button-danger">Excluir</a>
                </div>
            </div>
        </div>
        <div class="images-container">
            <?php foreach ($game["images"] as $image) { ?>
                <img src="<?= $image ?>" alt="<?= $game["name"] ?>">
            <?php } ?>
        </div>
        <div class="statistics">
            <div class="statistic-card">
                <h2>Vendas</h2>
                <p><?= $game["sells"] ?></p>
            </div>
            <div class="statistic-card">
                <h2>Favoritos</h2>
                <p><?= $favoritesCount ?></p>
            </div>
            <div class="statistic-card">
                <h2>Pedidos</h2>
                <p><?= $requestsCount ?></p>
            </div>
            <div class="statistic-card">
                <h2>Carrinhos</h2>
                <p><?= $isInCarts ?></p>
            </div>
        </div>
    </main>
    <dialog class="dialog" id="delete">
        <h1>Tem certeza que deseja excluir este jogo?</h1>
        <form action="../functions/games/remove.php" method="post">
            <input type="hidden" name="gameId" value="<?= $game["id"] ?>">
            <label for="password">Senha: <input type="password" name="password"></label>
            <p class="error" <?= isset($_GET["error"]) ? "" : "hidden" ?>><?= isset($_GET["error"]) ? $_GET["error"] : "" ?></p>
            <div>
                <a href="#" class="button">Cancelar</a>
                <button class="button button-danger">Excluir</button>
            </div>
        </form>
    </dialog>
</body>