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
    <link rel="shortcut icon" href="/imgs/logo-dark.png" type="image/x-icon">
    <title>Game Shop | Registrar</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-<?= isset($_GET["theme"]) ? $_GET["theme"] : "dark" ?>.css">
    <link rel="stylesheet" href="/auth/style.css">
    <script src="index.js" defer></script>
</head>
<body>

    <div class="container">
        <h1 style="text-align: center;">Game Shop | Login</h1>
        <form action="/functions/users/signUp.php" method="post">
            <img src="https://i.ytimg.com/vi/3gDcG9qDfME/hqdefault.jpg" alt="Imagem de perfil" id="avatar"  style="width: 200px;height: 200px;border-radius: 50%;object-fit: cover;margin: 10px auto;">
            <label for="photoUrl">
                Link da imagem de perfil:
                <input type="text" name="photoUrl" placeholder="https://linkdaimagem.com" id="photoUrl">
                <button onclick="setImage()" type="button" class="button">Usar imagem</button>
            </label>
            <label for="firstName">
                Nome:
                <input type="text" name="firstName" required placeholder="Silas">
            </label>

            <label for="lastName">
                Sobrenome:
                <input type="text" name="lastName" required placeholder="Silva">
            </label>

            <label for="email">
                Email:
                <input type="email" name="email" required placeholder="siladas@email.com">
            </label>

            <label for="password">
                Senha:
                <input type="password" name="password" required min="8" placeholder="12345678">
            </label>

            <label for="confirmPassword">
                Confirme sua senha:
                <input type="password" name="confirmPassword" required min="8" placeholder="12345678">
            </label>
            
            <button type="submit" class="button" style="margin-bottom: 10px;">Entrar</button>
        </form>

        <p class="error" <?= isset($_GET["error"]) ? "" : "hidden" ?>><?= isset($_GET["error"]) ? $_GET["error"] : "" ?></p>

        <div class="linkContainer">
            <a href="/auth/sign-in" class="link">Já tem uma conta?</a>
            <a href="/developer/sign-up" class="link">Você é um desenvolvedor?</a>
        </div>
    </div>
</body>