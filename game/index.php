<?php
    $users = json_decode(file_get_contents("../database/users.json"), true);
    $user = null;

    if (isset($_COOKIE["userId"])) {
        foreach ($users as $currentUser) {
            if ($currentUser["id"] == $_COOKIE["userId"]) {
                $user = $currentUser;
                break;
            }
        }
    }

    $games = json_decode(file_get_contents("../database/games.json"), true);
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

    if (isset($user)) {
        $favorites = json_decode(file_get_contents("../database/favorites.json"), true);
    
        $isFavorite = false;
        foreach ($favorites as $f) {
            if ($f["userId"] == $user["id"] && $f["gameId"] == $game["id"]) {
                $isFavorite = true;
                break;
            }
        }
    }


    $developersArray = json_decode(file_get_contents("../database/developers.json"), true);

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

    $requests = json_decode(file_get_contents("../database/requests.json"), true);

    $request = null;

    foreach ($requests as $r) {
        if (array_search($game["id"], $r["gamesId"]) !== false && $r["userId"] == $user["id"] && $r["status"] != "canceled") {
            $request = $r;
            break;
        }
    }

    $carts = json_decode(file_get_contents("../database/carts.json"), true);

    $isInCart = false;
    foreach ($carts as $c) {
        if ($c["userId"] == $user["id"] && $c["gameId"] == $game["id"]) {
            $isInCart = true;
            break;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Shop | <?= $game["name"] ?></title>
    <link rel="shortcut icon" href="/imgs/logo-<?= $user["theme"] ?? "dark" ?>.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-<?= $user !== null ? $user["theme"] : "dark" ?>.css">
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
        <form class="game-container" action="/functions/carts/<?= $isInCart ? "remove" : "add" ?>.php" method="post">
            <input type="hidden" name="gameId" value="<?= $game["id"] ?>">
            <img src="<?= $game["cover"] ?>" alt="<?= $game["name"] ?>" class="game-image">

            <div class="game-info">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <h1><?= $game["name"] ?></h1>
                    <?php if ($user !== null) { ?>
                        <a href="/functions/favorites/toogle.php?gameId=<?= $game["id"] ?>">
                            <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                            <svg fill="<?= $isFavorite ? "#007bff" : "transparent" ?>" height="2rem" width="2rem" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                viewBox="0 0 230 230" xml:space="preserve" class="favorite-icon <?= $isFavorite ? "favorite" : "not-favorite" ?>">
                            <path d="M213.588,120.982L115,213.445l-98.588-92.463C-6.537,96.466-5.26,57.99,19.248,35.047l2.227-2.083
                                c24.51-22.942,62.984-21.674,85.934,2.842L115,43.709l7.592-7.903c22.949-24.516,61.424-25.784,85.936-2.842l2.227,2.083
                                C235.26,57.99,236.537,96.466,213.588,120.982z"/>
                            </svg>
                        </a>
                    <?php } ?>
                </div>
                <a class="developerName" href="/developers/?id=<?= $developer["id"] ?>"><?= $developer["name"] ?></a>
                <p><?= $game["description"] ?></p>
                <span class="price">Preço: R$ <?= number_format($game["price"], 2, ",", ".") ?></span>
                <?php if ($request === null) { ?>
                    <?php if ($isInCart) { ?>
                        <button class="button button-danger">Remover do carrinho</button>
                    <?php } else { ?>
                        <button class="button">Adicionar ao carrinho</button>
                    <?php } ?>
                <?php } else { 
                    if ($request["status"] == "pending") { 
                ?>
                    <a href="/profile/requests/details/?id=<?= $request["id"] ?>" class="button">Ver pedido</a>
                    
                <?php } else { ?>
                    <a href="/profile/my-library/" class="button">Ver em minha biblioteca</a>
                <?php } } ?>
            </div>
        </form>
        <div class="images-container">
            <?php foreach ($game["images"] as $image) { ?>
                <img src="<?= $image ?>" alt="<?= $game["name"] ?>">
            <?php } ?>
        </div>
    </main>
</body>