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

    $gameId = $_GET["id"];
    $games = json_decode(file_get_contents("../../../database/games.json"), true);
    $game = null;
    foreach ($games as $currentGame) {
        if ($currentGame["id"] == $gameId && $currentGame["developerId"] == $developerId) {
            $game = $currentGame;
            break;
        }
    }

    if ($game == null) {
        header("location: /developer/game");
        return;
    }

    $categories = json_decode(file_get_contents("../../../database/categories.json"), true);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Jogo | &lt;Game Shop /&gt;</title>
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
        <h2>Editar Jogo <?= $game["name"] ?> - <?= $developer["name"] ?></h2>
        <hr>

        <form action="/developer/functions/games/update.php" method="post">
            <input type="hidden" name="gameId" value="<?= $game["id"] ?>">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" required value="<?= $game["name"] ?>">
            </div>
            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" cols="30" rows="10" required><?= $game["description"] ?></textarea>
            </div>
            <div class="form-group">
                <label for="cover">Capa:</label>
                <input type="text" name="cover" id="cover" placeholder="Link da imagem" required value="<?= $game["cover"] ?>">
                <button type="button" onclick="setCover()" class="button">Usar</button>
            </div>
            <img src="<?= $game["cover"] ?>" alt="" id="coverPreview">
            <div class="form-group">
                <label for="images">Imagens:</label>
                <input type="text" id="imageInput" placeholder="Link da imagem">
                <input type="hidden" name="images" id="images" value=" <?= implode(" ", $game["images"]) ?>">
                <button type="button" onclick="addImage()" class="button">Adicionar</button>
            </div>
            <div class="images-container" id="imagesContainer">
                <?php foreach ($game["images"] as $image) { ?>
                    <div class="image" style="background-image: url('<?= $image ?>'); background-size: cover" onclick=(removeImage(this))>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="image-trashIcon trashIcon" version="1.1" id="Capa_1" viewBox="0 0 408.483 408.483" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M87.748,388.784c0.461,11.01,9.521,19.699,20.539,19.699h191.911c11.018,0,20.078-8.689,20.539-19.699l13.705-289.316    H74.043L87.748,388.784z M247.655,171.329c0-4.61,3.738-8.349,8.35-8.349h13.355c4.609,0,8.35,3.738,8.35,8.349v165.293    c0,4.611-3.738,8.349-8.35,8.349h-13.355c-4.61,0-8.35-3.736-8.35-8.349V171.329z M189.216,171.329    c0-4.61,3.738-8.349,8.349-8.349h13.355c4.609,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.737,8.349-8.349,8.349h-13.355    c-4.61,0-8.349-3.736-8.349-8.349V171.329L189.216,171.329z M130.775,171.329c0-4.61,3.738-8.349,8.349-8.349h13.356    c4.61,0,8.349,3.738,8.349,8.349v165.293c0,4.611-3.738,8.349-8.349,8.349h-13.356c-4.61,0-8.349-3.736-8.349-8.349V171.329z"/>
                                    <path d="M343.567,21.043h-88.535V4.305c0-2.377-1.927-4.305-4.305-4.305h-92.971c-2.377,0-4.304,1.928-4.304,4.305v16.737H64.916    c-7.125,0-12.9,5.776-12.9,12.901V74.47h304.451V33.944C356.467,26.819,350.692,21.043,343.567,21.043z"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="price">Preço</label>
                <input type="number" step="0.01" name="price" id="price" required value="<?= $game["price"] ?>">
            </div>
            <div class="form-group">
                <label for="categoryId">Categorias</label>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category ?>" <?= $category == $game["category"] ? "selected" : "" ?>><?= $category ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="button">Postar</button>
        </form>
    </main>
</body>
</html>