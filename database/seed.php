<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('controllers/nursing-homes.php');
require_once fullPath('controllers/people-in-care.php');

nursingHomesController('sign_up', [
    'email'    => 'santamarcia@gmail.com',
    'password' => '123',
    'company_name' => 'Casa de Repouso Santa Márcia'
]);

$people = [
    [
        'first_name' => 'Maria',
        'last_name' => 'Silva',
        'birth_date' => '1938-04-10',
        'height' => 1.55,
        'weight' => 62,
        'allergies' => 'Dipirona',
        'notes' => null
    ],
    [
        'first_name' => 'João',
        'last_name' => 'Pereira',
        'birth_date' => '1945-11-21',
        'height' => 1.72,
        'weight' => 79,
        'allergies' => null,
        'notes' => 'Necessita de assistência para locomoção'
    ],
    [
        'first_name' => 'Ana',
        'last_name' => 'Santos',
        'birth_date' => '1980-07-15',
        'height' => 1.65,
        'weight' => 58,
        'allergies' => 'Amendoim',
        'notes' => null
    ],
    [
        'first_name' => 'Carlos',
        'last_name' => 'Oliveira',
        'birth_date' => '2008-09-05',
        'height' => 1.60,
        'weight' => 54,
        'allergies' => null,
        'notes' => 'Acompanhamento frequente para condição crônica'
    ],
    [
        'first_name' => 'Beatriz',
        'last_name' => 'Costa',
        'birth_date' => '1952-01-30',
        'height' => 1.60,
        'weight' => 67,
        'allergies' => null,
        'notes' => null
    ]
];


foreach ($people as $newPersonInCare) {
    peopleInCareController('create_person_in_care', [
        'first_name'      => $newPersonInCare['first_name'],
        'last_name'       => $newPersonInCare['last_name'],
        'birth_date'      => $newPersonInCare['birth_date'],
        'height'          => $newPersonInCare['height'],
        'weight'          => $newPersonInCare['weight'],
        'allergies'       => $newPersonInCare['allergies'],
        'notes'           => $newPersonInCare['notes']
    ]);
}

nursingHomesController('logout');
