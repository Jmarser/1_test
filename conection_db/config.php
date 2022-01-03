<?php

return[
    'db' => [
        'sgbd' => 'mysql:host',
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db_name' => 'ud2_test',
        'charset' => 'utf8',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    ]
];

?>