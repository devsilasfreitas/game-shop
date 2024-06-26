<?php
    if (!isset($_COOKIE['developerId'])) {
        header("location: ./sign-in");
        return;
    }

    $developerId = $_COOKIE['developerId'];
    $developers = json_decode(file_get_contents('../database/developers.json'), true);

    $developer = null;
    foreach ($developers as $d) {
        if ($d['id'] == $developerId) {
            $developer = $d;
            break;
        }
    }

    if ($developer === null) {
        setcookie('developerId', '', time() - 3600, '/developer');
        header("location: ./sign-in");
        return;
    }

    $games = json_decode(file_get_contents('../database/games.json'), true);
    $myGames = [];
    $mySells = 0;
    foreach ($games as $game) {
        if ($game['developerId'] == $developerId) {
            $myGames[$game['id']] = $game;
            $mySells += $game['sells'];
        }
    }

    $myBalance = 0;

    $requests = json_decode(file_get_contents('../database/requests.json'), true);
    $myRequests = [];
    foreach ($requests as $request) {
        foreach ($request['gamesId'] as $gameId) {
            if (isset($myGames[$gameId])) {
                $myRequests[] = $request;
                $myBalance += $request['price'];
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | &lt;Game Shop /&gt;</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-dark.css">
    <link rel="shortcut icon" href="/imgs/logo-dark.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
        <h1>Dashboard</h1>

        <div class="statistics">
            <div class="statistic-card">
                <h2><?= count($myGames) ?></h2>
                <p>Jogos</p>
            </div>

            <div class="statistic-card">
                <h2><?= count($myRequests) ?></h2>
                <p>Pedidos</p>
            </div>

            <div class="statistic-card">
                <h2><?= $mySells ?></h2>
                <p>Vendas</p>
            </div>

            <div class="statistic-card">
                <h2>R$ <?= number_format($myBalance, 2, ",", ".") ?></h2>
                <p>Saldo</p>
            </div>
        </div>

        <?php if (count($myGames) > 0) { ?>

            <div class="most-popular-games">
                <?php
                    $mostPopularGames = $myGames;
                    usort($mostPopularGames, function($a, $b) {
                        return $b["sells"] - $a["sells"];
                    });
                    $mostPopularGames = array_slice($mostPopularGames, 0, 20);
                ?>
        
                <h2>Mais vendidos</h2>
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($mostPopularGames as $mostPopularGame) { ?>
                            <div class="swiper-slide">
                                <a href="./game/?id=<?= $mostPopularGame["id"] ?>" class="game-card">
                                    <div class="card" style="background-image: url('<?= $mostPopularGame["cover"] ?>');">
                                        <div class="card-content">
                                            <h2 class="card-title"><?= $mostPopularGame["name"] ?></h3>
                                            <span class="card-price">R$ <?= number_format($mostPopularGame["price"], 2, ",", ".") ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination"></div>
            </div>

        <?php } ?>

        <section style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Meus jogos</h2>
            <a href="./game/create" class="button">Postar jogo</a>
        </section>

        <?php if (count($myGames) > 0) { ?>
            <div class="container-all-games">
                <?php foreach ($myGames as $g) { ?>
                    <a href="./game/?id=<?= $g["id"] ?>" class="game-card">
                        <div href="./game/?id=<?= $g["id"] ?>" class="card" style="background-image: url('<?= $g["cover"] ?>');">
                            <div class="card-content">
                                <h2 class="card-title"><?= $g["name"] ?></h3>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        <?php } else { ?>
                <p>Você ainda não tem jogos em sua biblioteca</p>
        <?php } ?>
        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".swiper", {
            slidesPerView: 6,
            spaceBetween: 10,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    </script>
</body>
</html>