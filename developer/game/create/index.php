<?php
    if (!isset($_COOKIE["developerId"])) {
        header("location: /developer/sign-in");
        return;
    }
    $developerId = $_COOKIE["developerId"];
    $developers = json_decode(file_get_contents("../../../database/developers.json"), true);
    $developer = null;
    foreach ($developers as $currentDeveloper) {
        if ($currentDeveloper["id"] == $developerId) {
            $developer = $currentDeveloper;
            break;
        }
    }
    if ($developer == null) {
        setcookie("developerId", "", time() - 3600, "/developer");
        header("location: /developer/sign-in");
        return;
    }

    $categories = json_decode(file_get_contents("../../../database/categories.json"), true);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postar Jogo | &lt;Game Shop /&gt;</title>
    <link rel="shortcut icon" href="/imgs/logo-dark.png" type="image/x-icon">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/theme-dark.css">
    <script src="../index.js" defer></script>
</head>
<body>
<header>
        <a href="/developer" class="logoContainer">
            <img src="/imgs/logo-dark.png" alt="Game Shop Logo" class="logo">
            <h1>&lt;Game Shop /&gt;</h1>
        </a>

        <div style="display: flex; align-items: center;gap: 15px">
            
            <div class="dropdown">
                <img src="<?= $developer["logoUrl"] ?>" alt="<?= $developer["name"] ?>"  class="avatar">
                <div class="dropdown-content">
                    <a href="/developer/profile">Perfil</a>
                    <hr>
                    <a href="/functions/developers/signOut.php">Sair da Conta</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <h2>Postar Jogo - <?= $developer["name"] ?></h2>
        <hr>

        <form action="/developer/functions/games/create.php" method="post">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" cols="30" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <label for="cover">Capa:</label>
                <input type="text" name="cover" id="cover" placeholder="Link da imagem" required>
                <button type="button" onclick="setCover()" class="button">Usar</button>
            </div>
            <img src="" alt="" id="coverPreview">
            <div class="form-group">
                <label for="images">Imagens:</label>
                <input type="text" id="imageInput" placeholder="Link da imagem">
                <input type="hidden" name="images" id="images">
                <button type="button" onclick="addImage()" class="button">Adicionar</button>
            </div>
            <div class="images-container" id="imagesContainer"></div>

            <div class="form-group">
                <label for="price">Preço</label>
                <input type="number" step="0.01" name="price" id="price" required>
            </div>
            <div class="form-group">
                <label for="categoryId">Categorias</label>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category ?>"><?= $category ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="button">Postar</button>
        </form>
    </main>
</body>
</html>