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
        header("Location: /auth/sign-in");
        return;
    }

    $requestsArray = json_decode(file_get_contents("../../database/requests.json"), true);

    $games = json_decode(file_get_contents("../../database/games.json"), true);

    $requests = [];
    foreach ($requestsArray as $request) {
        if ($request["userId"] == $userId) {
            $requests[] = $request;
        }
    }

    $statusList = [
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
    <link rel="stylesheet" href="/css/theme-<?= $user["theme"] ?? "dark" ?>.css">
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
        <h1>Meus pedidos</h1>

        <?php if (count($requests) == 0) { ?>
            <p>Sem pedidos</p>
        <?php } else { ?>
            <div class="requestContainer">
                <?php foreach ($requests as $request) { ?>
                    <a class="request" href="/profile/requests/details/?id=<?= $request["id"] ?>">
                        <h3>Pedido #<?= $request["id"] ?><span class="<?= $request["status"] ?> status"> <?= $statusList[$request["status"]] ?></span></h3>
                        <p>
                            <?php
                                $gamesId = [];
                                foreach ($request["gamesId"] as $currentGame) {
                                    $gamesId[] = $currentGame;
                                }
                                foreach ($games as $currentGame) {
                                    $index = array_search($currentGame["id"], $gamesId);
                                    if ($index !== false) {
                                        ?>
                                            <p><?= $currentGame["name"] ?>: <?= "R$ ".number_format($currentGame["price"], 2, ",", ".") ?></p>
                                        <?php
                                    }
                                }
                            ?>
                        </p>
                        <h4>Total: R$ <?= number_format($request["price"], 2, ",", ".") ?></h4>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </main>