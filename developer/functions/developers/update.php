<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $logoUrl = strlen($_POST["logoUrl"]) < 2 ? "https://th.bing.com/th/id/OIP.hWJZ14W9Wgj_Im75mOVGTAHaHa?rs=1&pid=ImgDetMain" : $_POST["logoUrl"];
        $banner = strlen($_POST["banner"]) < 2 ? "https://th.bing.com/th/id/OIP.hWJZ14W9Wgj_Im75mOVGTAHaHa?rs=1&pid=ImgDetMain" : $_POST["banner"];
        $developerId = $_COOKIE["developerId"];

        $developers = json_decode(file_get_contents("../../../database/developers.json"), true);

        foreach ($developers as $index => $developer) {
            if ($developer["id"] == $developerId) {
                $developer["name"] = $name;
                $developer["description"] = $description;
                $developer["logoUrl"] = $logoUrl;
                $developer["banner"] = $banner;
    
                $developers[$index] = $developer;
    
                file_put_contents("../../../database/developers.json", json_encode($developers, JSON_PRETTY_PRINT)); 
            }
        }

        header("Location: /developer/profile/?id=" . $developerId);
    }