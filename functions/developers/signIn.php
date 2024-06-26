<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $developers = json_decode(file_get_contents("../../database/developers.json"), true);
        $developer = null;

        foreach ($developers as $currentDeveloper) {
            if ($currentDeveloper['email'] == $email) {
                $developer = $currentDeveloper;
                break;
            }
        }

        if ($developer === null) {
            header("location: /developer/sign-in?error=Este email não existe".$developers[0]['email']);
            return;
        }

        if ($developer['password'] != $password) {
            header("location: /developer/sign-in?error=Senha incorreta");
            return;
        }

        setcookie('developerId', $developer['id'], time() + 3600, '/developer');
        header("location: /developer");
    }