<?php
        $userId = $_COOKIE["userId"];

        $users = json_decode(file_get_contents("../../database/users.json"), true);
        foreach ($users as $index => $user) {
            if ($user["id"] == $userId) {
                $user["theme"] = $user["theme"] == "dark" ? "light" : "dark";

                $users[$index] = $user;

                file_put_contents("../../database/users.json", json_encode($users));

                break;
            }
        }

        header("Location: /profile/");
