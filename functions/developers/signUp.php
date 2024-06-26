<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['email']) && isset($_POST['password'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $banner = $_POST['banner'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $logoUrl = $_POST['logoUrl'] ?? 'https://th.bing.com/th/id/OIP.hWJZ14W9Wgj_Im75mOVGTAHaHa?rs=1&pid=ImgDetMain';
            
            $developer = [
                'name' => $name,
                'description' => $description,
                'banner' => $banner,
                'email' => $email,
                'password' => $password,
                'logoUrl' => $logoUrl,
                'theme' => 'dark'
            ];

            $developers = json_decode(file_get_contents('../../database/developers.json'), true);

            foreach ($developers as $currentDeveloper) {
                if ($currentDeveloper['email'] == $email) {
                    header("location: /auth/sign-up?error=Este email ja existe");
                    return;
                }
            }

            if ($password != $confirmPassword) {
                header("location: /auth/sign-up?error=As senhas precisam ser iguais");
                return;
            }

            $developer['id'] = uniqid();

            $developers[] = $developer;

            file_put_contents('../../database/developers.json', json_encode($developers));

            header('location: /developer/sign-in/');
        }
    }