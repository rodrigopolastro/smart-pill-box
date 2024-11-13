<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('controllers/nursing-homes.php');

nursingHomesController('sign_up', [
    'email'    => 'santamarcia@gmail.com',
    'password' => '123',
    'company_name' => 'Casa de Repouso Santa Márcia'
]);

nursingHomesController('logout');
