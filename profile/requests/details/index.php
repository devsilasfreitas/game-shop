<?php
    $users = json_decode(file_get_contents("../../../database/users.json"), true);
    $user = null;

    foreach ($users as $u) {
        if ($u['id'] == $_COOKIE['userId']) {
            $user = $u;
            break;
        }
    }

    if ($user == null) {
        header("location: /auth/sign-in");
        return;
    }

    $requests = json_decode(file_get_contents("../../../database/requests.json"), true);

    $request = null;
    foreach ($requests as $currentRequest) {
        if ($currentRequest['id'] == $_GET['id']) {
            $request = $currentRequest;
            break;
        }
    }

    if ($request == null) {
        header("location: /erro/404.php?erro=Requisição%20n%C3%A3o%20encontrada&id=" . $_GET['id']);
        return;
    }

    if ($request['userId'] != $user['id']) {
        header("location: /erro/404.php?erro=Requisição%20n%C3%A3o%20encontrada&id=" . $_GET['id']);
        return;
    }

    $gamesArray = json_decode(file_get_contents("../../../database/games.json"), true);
    $games = [];
    $gamesId = [];

    foreach ($request['gamesId'] as $game) {
        $gamesId[] = $game;
    }

    foreach ($gamesArray as $game) {

        if (in_array($game['id'], $gamesId)) {
            $games[$game["id"]] = $game;
        }
    }

    if (count($games) == 0) {
        header("location: /erro/404.php?erro=Requisição%20n%C3%A3o%20encontrada&id=" . $_GET['id']);
        return;
    }

    $statusList =[
        "pending" => "Pendente",
        "finished" => "Finalizado",
        "canceled" => "Cancelado"
    ]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-<?= $user["theme"] ?>.css">
    <link rel="shortcut icon" href="/imgs/logo-<?= $user["theme"] ?? "dark" ?>.png" type="image/x-icon">
    <title>Meus pedidos | Game Shop</title>
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
        <h1>Pedido #<?= $request['id'] ?> <span class="<?= $request["status"] ?>"><?= $statusList[$request["status"]] ?></span></h1>

        <h2>Jogos solicitados: </h2>
        
        <div class="games">
            <?php foreach ($request["gamesId"] as $game) { 
                $currentGame = $games[$game];?>
                <div class="game">
                    <img src="<?= $currentGame["cover"] ?>" alt="<?= $currentGame["name"] ?>" class="gameImage">
                    <div class="gameInfo">
                        <h2><?= $currentGame["name"] ?></h2>
                        <p>Preço: R$ <?= number_format($currentGame["price"], 2, ",", ".") ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h3>Total: R$ <?= number_format($request["price"], 2, ",", ".") ?></h3>

        <?php if ($request["status"] == "pending") { ?>

            <a class="button button-danger" href="#cancel">Cancelar pedido</a>
            <a href="#payment" class="button">Pagar Agora</a>

            <dialog id="payment" class="dialog">
                <h1>Pagar pedido #<?= $request['id'] ?></h1>
                <form action="/functions/requests/update.php" method="post">
                    <input type="hidden" name="requestId" value="<?= $request['id'] ?>">
                    <input type="hidden" name="status" value="finished">
                    <h2>Total: R$ <?= number_format($request['price'], 2, ",", ".") ?></h2>
                    <div>
                        <a href="#" class="button-danger button">Cancelar</a>
                        <button class="button">Pagar</button>
                    </div>
                </form>
            </dialog>
            <dialog id="cancel" class="dialog">
                <h1>Cancelar pedido #<?= $request['id'] ?></h1>
                <h2>Tem certeza que deseja cancelar este pedido?</p>	
                <form action="/functions/requests/update.php" method="post">
                    <input type="hidden" name="requestId" value="<?= $request['id'] ?>">
                    <input type="hidden" name="status" value="canceled">
                    <div>
                        <a href="#" class="button">Não</a>
                        <button class="button button-danger">Sim</button>
                    </div>
                </form>
            </dialog>

        <?php } ?>
    </main>
    
</body>