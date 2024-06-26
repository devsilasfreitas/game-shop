<?php 

    // Verifica se o usário está logado
    $users = json_decode(file_get_contents("database/users.json"), true);
    $user = null;

    if (isset($_COOKIE["userId"])) {
        foreach ($users as $currentUser) {
            if ($currentUser["id"] == $_COOKIE["userId"]) {
                $user = $currentUser;
                break;
            }
        }

        if ($user === null) {
            setcookie("userId", "", time() - 3600, "/");
        }

    }
    
    $categories = json_decode(file_get_contents("database/categories.json"), true);
    usort($categories, function($a, $b) {
        return $a <=> $b;
    });

    $games = json_decode(file_get_contents("database/games.json"), true);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Game Shop</title>
    <link rel="shortcut icon" href="/imgs/logo-<?= $user["theme"] ?? "dark" ?>.png" type="image/x-icon">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/theme-<?= $user ? $user["theme"] : "dark" ?>.css">
</head>
<body>
    <header>
        <a href="/" class="logoContainer">
            <img src="/imgs/logo-<?= $user["theme"] ?? "dark" ?>.png" alt="Game Shop Logo" class="logo">
            <h1>Game Shop</h1>
        </a>

        <?php if (isset($user) && $user !== null) { ?>
            <div style="display: flex; align-items: center;gap: 15px">
                <a href="/profile/my-cart">
                    <svg fill="none" height="27" viewBox="0 0 30 27" width="30" xmlns="http://www.w3.org/2000/svg"><path d="M1.39999 1.70001H6.60001" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><path d="M6.60001 1.70001L11 18.9" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><path d="M11.8 18.9H28.3" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><path d="M13.8 25.7C15.4569 25.7 16.8 24.3569 16.8 22.7C16.8 21.0432 15.4569 19.7 13.8 19.7C12.1431 19.7 10.8 21.0432 10.8 22.7C10.8 24.3569 12.1431 25.7 13.8 25.7Z" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><path d="M25.3 25.7C26.9568 25.7 28.3 24.3569 28.3 22.7C28.3 21.0432 26.9568 19.7 25.3 19.7C23.6431 19.7 22.3 21.0432 22.3 22.7C22.3 24.3569 23.6431 25.7 25.3 25.7Z" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/><path d="M25.7 14.6H11.3C10.7 14.6 10.1 14.2 10 13.6L8.1 6.90001C7.9 6.00001 8.49999 5.20001 9.39999 5.20001H27.5C28.4 5.20001 29.1 6.10001 28.8 6.90001L26.9 13.6C26.9 14.2 26.4 14.6 25.7 14.6Z" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"/></svg>
                </a>
                <div class="dropdown">
                    <img src="<?= $user["photoUrl"] ?>" alt="<?= $user["firstName"] ?>"  class="avatar">
                    <div class="dropdown-content">
                        <a href="/profile/">Perfil</a>
                        <a href="/profile/requests/">Meus pedidos</a>
                        <hr>
                        <a href="/functions/users/signOut.php">Sair da Conta</a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div style="display: flex; align-items: center;gap: 15px">
                <a href="/auth/sign-in/" class="button">Login</a>
                <a href="/auth/sign-up/" class="link">Cadastrar</a>
            </div>
        <?php } ?>
    </header>
    <main>
        <div class="most-popular-games">
            <?php
                $mostPopularGames = $games;
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
                            <a href="/game/?id=<?= $mostPopularGame["id"] ?>" class="game-card">
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

        <div id="last-games">
            <h2>Últimos lançamentos</h2>
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php $lastGames = $games;
                        $lastGames = array_slice(array_reverse($lastGames), 0, 20);
                     ?>

                    <?php foreach ($lastGames as $lastGame) { ?>
                        <div class="swiper-slide">
                            <a href="/game/?id=<?= $lastGame["id"] ?>" class="game-card">
                                <div class="card" style="background-image: url('<?= $lastGame["cover"] ?>');">
                                    <div class="card-content">
                                        <h2 class="card-title"><?= $lastGame["name"] ?></h3>
                                        <span class="card-price">R$ <?= number_format($lastGame["price"], 2, ",", ".") ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="categories">
            <h2>Categorias</h2>
            <div class="container-categories">
                <?php foreach ($categories as $category) { ?>
                    <a href="/category/?name=<?= $category ?>" class="category-card">
                        <?= $category ?>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="all-developers">
            <?php
                $developers = json_decode(file_get_contents("./database/developers.json"), true);
                usort($developers, function($a, $b) {
                    return $a["name"] <=> $b["name"];
                });
            ?>
            <h2>Desenvolvedores</h2>
            <div class="container-developers">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($developers as $developer) { ?>
                            <div class="swiper-slide">
                                <a href="/developers/?id=<?= $developer["id"] ?>" class="game-card">
                                    <div class="card" style="background-image: url('<?= $developer["logoUrl"] ?>');">
                                        <div class="card-content">
                                            <h2 class="card-title"><?= $developer["name"] ?></h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>

        <div class="all-games">
            <?php
                $allGames = $games;
                usort($allGames, function($a, $b) {
                    return $a["name"] <=> $b["name"];
                });
            ?>
            <h2>Todos os jogos</h2>
            <div class="container-all-games">
                <?php foreach ($allGames as $allGame) { ?>
                    <a href="/game/?id=<?= $allGame["id"] ?>" class="game-card">
                        <div class="card" style="background-image: url('<?= $allGame["cover"] ?>');">
                            <div class="card-content">
                                <h2 class="card-title"><?= $allGame["name"] ?></h3>
                                <span class="card-price">R$ <?= number_format($allGame["price"], 2, ",", ".") ?></span>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
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