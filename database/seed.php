<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('controllers/nursing-homes.php');
require_once fullPath('controllers/people-in-care.php');
require_once fullPath('controllers/medicines.php');
require_once fullPath('controllers/treatments.php');

//========== SIGN UP (AND LOGIN) //==========
nursingHomesController('sign_up', [
    'email'    => 'santamarcia@gmail.com',
    'password' => '123',
    'company_name' => 'Casa de Repouso Santa Márcia'
]);

//========== PEOPLE IN CARE CREATION //==========
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

//========== MEDICINES CREATION //==========
$medicines = [
    [
        'nursing_home_id' => 1,
        'name' => 'Dipirona',
        'description' => 'Anti-Inflamatório',
        'quantity_pills' => 10,
        'price' => 12.99,
    ],
    [
        'nursing_home_id' => 1,
        'name' => 'Plasil',
        'description' => 'Indicado para Febre',
        'quantity_pills' => 20,
        'price' => 5.20,
    ],
    [
        'nursing_home_id' => 1,
        'name' => 'Buscopan',
        'description' => 'Butilbrometo de Escopolamina',
        'quantity_pills' => 15,
        'price' => 22.50,
    ],
];

foreach ($medicines as $medicine) {
    medicinesController('create_medicine', [
        'nursing_home_id' => $medicine['nursing_home_id'],
        'name' => $medicine['name'],
        'description' => $medicine['description'],
        'quantity_pills' => $medicine['quantity_pills'],
        'price' => $medicine['price']
    ]);
}

//========== TRETMENTS CREATION //==========
$treatments = [
    [
        'person_in_care_id' => '1',
        'medicine_id' => '1',
        'status' => 'ongoing',
        'reason' => 'gripe',
        'usage_frequency' => 'daily',
        'start_date' => '2024-11-13',
        'doses_per_day' => 1,
        'pills_per_dose' => 1,
        'total_usage_days' => 5,
        'slot_name' => 'A',
        'doses_times' => ['10:00']
    ],
    [
        'person_in_care_id' => '2',
        'medicine_id' => '2',
        'status' => 'ongoing',
        'reason' => 'virose',
        'usage_frequency' => 'daily',
        'start_date' => '2024-11-13',
        'doses_per_day' => 2,
        'pills_per_dose' => 1,
        'total_usage_days' => 3,
        'slot_name' => 'A',
        'doses_times' => ['06:00', '18:00']
    ],
    [
        'person_in_care_id' => '3',
        'medicine_id' => '3',
        'status' => 'ongoing',
        'reason' => 'dor de cabeça',
        'usage_frequency' => 'daily',
        'start_date' => '2024-11-13',
        'doses_per_day' => 3,
        'pills_per_dose' => 1,
        'total_usage_days' => 2,
        'slot_name' => 'A',
        'doses_times' => ['06:00', '14:00', '22:00']
    ],
];

foreach ($treatments as $treatment) {
    treatmentsController('create_treatment', [
        'person_in_care_id' => $treatment['person_in_care_id'],
        'medicine_id' => $treatment['medicine_id'],
        'status' => $treatment['status'],
        'reason' => $treatment['reason'],
        'usage_frequency' => $treatment['usage_frequency'],
        'start_date' => $treatment['start_date'],
        'doses_per_day' => $treatment['doses_per_day'],
        'pills_per_dose' => $treatment['pills_per_dose'],
        'total_usage_days' => $treatment['total_usage_days'],
        'slot_name' => $treatment['slot_name'],
        'doses_times' => $treatment['doses_times']
    ]);
}

//========== LOGOUT //==========
nursingHomesController('logout');
