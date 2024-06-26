<?php
    $userId = $_COOKIE["userId"];
    
    $users = json_decode(file_get_contents("../../database/users.json"), true);
    $user = null;
    foreach ($users as $currentUser) {
        if ($currentUser["id"] == $userId) {
            $user = $currentUser;
            break;
        }
    }

    if ($user == null) {
        header("location: /auth/sign-in");
        return;
    }

    $allGames = json_decode(file_get_contents("../../database/games.json"), true);
    
    $carts = json_decode(file_get_contents("../../database/carts.json"), true);
    $cart = [];
    $games = [];
    foreach ($carts as $currentCart) {
        if ($currentCart["userId"] == $user["id"]) {
            $cart[] = $currentCart;
            foreach ($allGames as $currentGame) {
                if ($currentCart["gameId"] == $currentGame["id"]) {
                    $games[] = $currentGame;
                }
            } 

            if (count($games) == 0) {
                header("location: /erro/404.php?erro=Jogo%20n%C3%A3o%20encontrado&id=" . $currentCart["gameId"]);
                return;
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-<?= $user["theme"] ?>.css">
    <title>Carrinho<?= count($cart) > 0 ? "(" . count($cart) . ")" : "" ?> | Game Shop</title>
    <link rel="shortcut icon" href="/imgs/logo-<?= $user["theme"] ?? "dark" ?>.png" type="image/x-icon">
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
        <h1>Meu carrinho</h1>
        <p><?= count($cart) > 0 ? "Total de itens: " . count($cart) : "Seu carrinho está vazio" ?></p>
        <div class="requestContainer">
            <?php if (count($cart) > 0) { ?>
                <?php $total = 0; ?>
                <?php foreach ($games as $index => $currentGame) { ?>
                    <?php $cartItem = $cart[$index]; ?>
                    <?php $total += $currentGame["price"] ?>
                    <div class="cartItem">
                        <img src="<?= $currentGame["cover"] ?>" alt="<?= $currentGame["name"] ?>" class="cartItemImage">
                        <div>
                            <h2><?= $currentGame["name"] ?></h2>
                            <p>R$ <?= $currentGame["price"] ?></p>
                        </div>
                        <form action="/functions/carts/remove.php" method="post" style="margin-left: auto;">
                            <input type="hidden" name="gameId" value="<?= $currentGame["id"] ?>">
                            <button style="background-color: transparent; border: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="trashIcon" version="1.1" id="Capa_1" viewBox="0 0 408.483 408.483" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path d="M87.748,388.784c0.461,11.01,9.521,19.699,20.539,19.699h191.911c11.018,0,20.078-8.689,20.539-19.699l13.705-289.316    H74.043L87.748,388.784z M247.655,171.329c0-4.61,3.738-8.349,8.35-8.349h13.355c4.609,0,8.35,3.738,8.35,8.349v165.293    c0,4.611-3.738,8.349-8.35,8.349h-13.355c-4.61,0-8.35-3.736-8.35-8.349V171.329z M189.216,171.329    c0-4.61,3.738-8.349,8.349-8.349h13.355c4.609,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.737,8.349-8.349,8.349h-13.355    c-4.61,0-8.349-3.736-8.349-8.349V171.329L189.216,171.329z M130.775,171.329c0-4.61,3.738-8.349,8.349-8.349h13.356    c4.61,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.738,8.349-8.349,8.349h-13.356c-4.61,0-8.349-3.736-8.349-8.349V171.329z"/>
                                            <path d="M343.567,21.043h-88.535V4.305c0-2.377-1.927-4.305-4.305-4.305h-92.971c-2.377,0-4.304,1.928-4.304,4.305v16.737H64.916    c-7.125,0-12.9,5.776-12.9,12.901V74.47h304.451V33.944C356.467,26.819,350.692,21.043,343.567,21.043z"/>
                                        </g>
                                    </g>
                                </svg>
                            </button>
                        </form>
                    </div>
                    <?php } ?>
                <h2>Total: R$ <?= $total ?></h2>
            <?php } ?>
        </div>
        <?php if (count($cart) > 0) { ?>
            <a href="/functions/requests/create.php" class="button">Finalizar compra</a>
        <?php } ?>

        
    </main>
</body>
</html>