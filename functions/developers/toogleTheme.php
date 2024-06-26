<?php
        $userId = $_COOKIE["developerId"];

        $developers = json_decode(file_get_contents("../../database/developers.json"), true);
        foreach ($developers as $index => $developer) {
            if ($developer["id"] == $developerId) {
                $developer["theme"] = $developer["theme"] == "dark" ? "light" : "dark";

                $developers[$index] = $developer;

                file_put_contents("../../database/developers.json", json_encode($developers));

                break;
            }
        }

        header("Location: /developer/profile/");
