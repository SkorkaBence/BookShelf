<?php

$_CONFIG = [
    "mysql" => [
        "host" => "",                 // MySQL Host
        "username" => "",             // MySQL Username
        "password" => "",             // MySQL Password
        "database" => ""              // MySQL Database
    ],
    "recaptcha" => [
        "site_key" => "",             // Goolge reCaptcha site key
        "secret" => ""                // Google reCaptcha secret key
    ],
    "google" => [
        "server_key" => ""            // Google API server key
    ],
    "uploaded_files" => "local",      // Where to store uploaded file:
                                      //     "local" - upload them, and store them in local storage
                                      //     "azure" - upload them into the azure cloud
    "azure" => [
        "storage" => [
            "container_name" => "",   // Microsoft Azure blob container name
            "connection_string" => "" // Microsoft Azure blob container access token, including the account name and key
        ]
    ]
];
