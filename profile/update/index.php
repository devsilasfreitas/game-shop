<?php
    $users = json_decode(file_get_contents("../../database/users.json"), true);

    $user = null;

    foreach ($users as $currentUser) {
        if ($currentUser["id"] == $_COOKIE["userId"]) {
            $user = $currentUser;
            break;
        }
    }

    if ($user === null) {
        setcookie("userId", "", time() - 3600, "/");
        header("location: /auth/sign-in");
        return;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar perfil | <?= $user["firstName"] ?></title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-<?= $user["theme"] ?>.css">
    <link rel="stylesheet" href="/auth/style.css">
    <link rel="shortcut icon" href="/imgs/logo-<?= $user["theme"] ?? "dark" ?>.png" type="image/x-icon">
    <script src="index.js" defer></script>
</head>

<body>

    <div class="container">
        <form action="/functions/users/update.php" method="post">
            <img src="<?= $user["photoUrl"] ?>" alt="Imagem de perfil" id="avatar" style="width: 200px;height: 200px;border-radius: 50%;object-fit: cover;margin: 10px auto;">
            <label for="photoUrl">
                Link da imagem de perfil:
                <input type="text" name="photoUrl" placeholder="https://linkdaimagem.com" id="photoUrl" value="<?= $user["photoUrl"] ?>">
                <button onclick="setImage()" type="button" class="button">Usar imagem</button>
            </label>
            <label for="firstName">
                Nome:
                <input type="text" name="firstName" required placeholder="Silas" value="<?= $user["firstName"] ?>">
            </label>

            <label for="lastName">
                Sobrenome:
                <input type="text" name="lastName" required placeholder="Silva" value="<?= $user["lastName"] ?>">
            </label>

            <button type="submit" class="button" style="margin-bottom: 10px;">Salvar</button>
        </form>

        <p class="error" <?= isset($_GET["error"]) ? "" : "hidden" ?>><?= isset($_GET["error"]) ? $_GET["error"] : "" ?></p>
    </div>
</body>

</html>