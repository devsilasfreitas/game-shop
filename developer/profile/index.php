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
    $myGames = [];

    foreach ($games as $currentGame) {
        if ($currentGame["developerId"] == $developer["id"]) {
            $myGames[] = $currentGame;
        }
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do desenvolvedor | &lt;Game Shop /&gt;</title>
    <link rel="shortcut icon" href="/imgs/logo-dark.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-dark.css">
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
        <div class="container">
            <img src="<?= $developer["banner"] ?>" alt="<?= $developer["name"] ?> banner" class="banner">

            <section class="profile">
                <img src="<?= $developer["logoUrl"] ?>" alt="<?= $developer["name"] ?>" class="logo-profile">
                <div class="info">
                    <h1><?= $developer["name"] ?></h1>
                    <p><?= $developer["description"] ?></p>
                </div>
                <a href="./update/">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" viewBox="0 0 306.637 306.637" xml:space="preserve" class="edit-profile">
                        <g>
                            <g>
                                <path d="M12.809,238.52L0,306.637l68.118-12.809l184.277-184.277l-55.309-55.309L12.809,238.52z M60.79,279.943l-41.992,7.896    l7.896-41.992L197.086,75.455l34.096,34.096L60.79,279.943z"/>
                                <path d="M251.329,0l-41.507,41.507l55.308,55.308l41.507-41.507L251.329,0z M231.035,41.507l20.294-20.294l34.095,34.095    L265.13,75.602L231.035,41.507z"/>
                            </g>
                        </g>
                    </svg>
                </a>
            </section>
            <hr>
            <h2>Jogos da <?= $developer["name"] ?></h2>
            <div class="container-all-games">
                <?php foreach ($myGames as $g) { ?>
                    <div class="card" style="background-image: url('<?= $g["cover"] ?>');">
                        <div class="card-content">
                            <h2 class="card-title"><?= $g["name"] ?></h3>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>