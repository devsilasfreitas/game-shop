<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $users = json_decode(file_get_contents("../../database/users.json"), true);
        $user = null;

        foreach ($users as $currentUser) {
            if ($currentUser['email'] == $email) {
                $user = $currentUser;
                break;
            }
        }

        if ($user === null) {
            header("location: /auth/sign-in?error=Este email não existe");
            return;
        }

        if ($user['password'] != $password) {
            header("location: /auth/sign-in?error=Senha incorreta");
            return;
        }

        setcookie('userId', $user['id'], time() + 3600, '/');
        header("location: /");
    }