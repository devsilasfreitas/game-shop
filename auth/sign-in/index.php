<?php

    if (isset($_COOKIE["userId"])) {
        header("location: /");
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Shop | Login</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-<?= isset($_GET["theme"]) ? $_GET["theme"] : "dark" ?>.css">
    <link rel="stylesheet" href="/auth/style.css">
    <link rel="shortcut icon" href="/imgs/logo-dark.png" type="image/x-icon">
</head>
<body>

    <div class="container">
        <form action="/functions/users/signIn.php" method="post">
            <h1 style="text-align: center;">Game Shop | Login</h1>

            <label for="email">
                Email:
                <input type="email" name="email" required placeholder="siladas@email.com">
            </label>

            <label for="password">
                Senha:
                <input type="password" name="password" required min="8" placeholder="12345678">
            </label>
            
            <button type="submit" class="button" style="margin-bottom: 10px">Entrar</button>
        </form>

        <p class="error" <?= isset($_GET["error"]) ? "" : "hidden" ?>><?= isset($_GET["error"]) ? $_GET["error"] : "" ?></p>

        <div class="linkContainer">
            <a href="/auth/sign-up" class="link">Não tem uma conta?</a>
            <a href="/developer/sign-in/" class="link">Voce é um desenvolvedor?</a>
        </div>

    </div>
</body>