<?php

    if (isset($_COOKIE["developerId"])) {
        header("location: /developer");
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
        <h1 style="text-align: center;">Game Shop | Registrar</h1>
        <form action="/functions/developers/signUp.php" method="post">
            <img src="https://i.ytimg.com/vi/3gDcG9qDfME/hqdefault.jpg" alt="banner" style="width: 100%; height: calc(40vw * 0.25); object-fit: cover" id="banner">
            <label for="bannerUrl" class="banner">
                Link da imagem de banner:
                <input type="text" name="banner" placeholder="https://linkdaimagem.com" id="bannerUrl">
                <button onclick="setBanner()" type="button" class="button">Usar imagem</button>
            </label>

            <img src="https://i.ytimg.com/vi/3gDcG9qDfME/hqdefault.jpg" alt="Imagem da logo" id="logo"  style="width: 200px;height: 200px;border-radius: 50%;object-fit: cover;margin: 10px auto;">
            <label for="logoUrl">
                Link da imagem da logo:
                <input type="text" name="logoUrl" placeholder="https://linkdaimagem.com" id="logoUrl">
                <button onclick="setImage()" type="button" class="button">Usar imagem</button>
            </label>

            <label for="name">
                Nome da Empresa:
                <input type="text" name="name" required placeholder="Rockstar">
            </label>

            <label for="description">
                Descricão:
                <input type="text" name="description" required placeholder="Maior desenvolvedor de jogos">
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
            <a href="/developer/sign-in" class="link">Já tem uma conta?</a>
            <a href="/auth/sign-up/" class="link">Não é um desenvolvedor?</a>
        </div>
    </div>
</body>