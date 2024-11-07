<?php
$config = [
    'db' => [
        'connection' => 'mysql:host=localhost',
        'dbname' => 'subhasta',
        'usr' => 'root',
        'pwd' => '1234',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ],
    ],
    'depuracion' => true,
];

return $config;
?>