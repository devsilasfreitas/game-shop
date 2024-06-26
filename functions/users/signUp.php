<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['password'])) {
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $photoUrl = $_POST['photoUrl'] ?? 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50';
            
            $user = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password,
                'photoUrl' => $photoUrl,
                'theme' => 'dark'
            ];

            $users = json_decode(file_get_contents('../../database/users.json'), true);

            foreach ($users as $currentUser) {
                if ($currentUser['email'] == $email) {
                    header("location: /auth/sign-up?error=Este email ja existe");
                    return;
                }
            }

            if ($password != $confirmPassword) {
                header("location: /auth/sign-up?error=As senhas precisam ser iguais");
                return;
            }

            $user['id'] = uniqid();

            $users[] = $user;

            file_put_contents('../../database/users.json', json_encode($users));

            header('location: /auth/sign-in/');
        }
    }