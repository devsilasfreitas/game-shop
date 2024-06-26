<?php
    if (!isset($_COOKIE["developerId"])) {
        header("location: /developer/sign-in");
        return;
    }

    $developers = json_decode(file_get_contents("../../../database/developers.json"), true);
    $developer = null;
    foreach ($developers as $currentDeveloper) {
        if ($currentDeveloper["id"] == $_COOKIE["developerId"]) {
            $developer = $currentDeveloper;
            break;
        }
    }

    if ($developer == null) {
        setcookie("developerId", "", time() - 3600, "/developer");
        header("location: /developer/sign-in");
        return;
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/imgs/logo-dark.png" type="image/x-icon">
    <title>Atualizar perfil | &lt;GameShop /&gt;</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-<?= isset($_GET["theme"]) ? $_GET["theme"] : "dark" ?>.css">
    <link rel="stylesheet" href="/auth/style.css">
    <script src="index.js" defer></script>
</head>
<body>

    <div class="container">
        <h1 style="text-align: center;">Game Shop | Atualizar perfil</h1>
        <form action="/developer/functions/developers/update.php" method="post">
            <img src="<?= $developer["banner"] ?>" alt="banner" style="width: 100%; height: calc(40vw * 0.25); object-fit: cover" id="banner">
            <label for="bannerUrl" class="banner">
                Link da imagem de banner:
                <input type="text" name="banner" value="<?= $developer["banner"] ?>" placeholder="https://linkdaimagem.com" id="bannerUrl">
                <button onclick="setBanner()" type="button" class="button">Usar imagem</button>
            </label>

            <img src="<?= $developer["logoUrl"] ?>" alt="Imagem da logo" id="logo"  style="width: 200px;height: 200px;border-radius: 50%;object-fit: cover;margin: 10px auto;">
            <label for="logoUrl">
                Link da imagem da logo:
                <input type="text" name="logoUrl" value="<?= $developer["logoUrl"] ?>" placeholder="https://linkdaimagem.com" id="logoUrl">
                <button onclick="setImage()" type="button" class="button">Usar imagem</button>
            </label>

            <label for="name">
                Nome da Empresa:
                <input type="text" name="name" value="<?= $developer["name"] ?>" required placeholder="Rockstar">
            </label>

            <label for="description">
                Descricão:
                <input type="text" name="description" value="<?= $developer["description"] ?>" required placeholder="Maior desenvolvedor de jogos">
            </label>

            <button type="submit" class="button" style="margin-bottom: 10px;">Salvar</button>
        </form>

        <p class="error" <?= isset($_GET["error"]) ? "" : "hidden" ?>><?= isset($_GET["error"]) ? $_GET["error"] : "" ?></p>
    </div>
</body>