INSERT INTO NURSING_HOMES (
    NSH_name,
    NSH_email,
    NSH_password
) VALUES (
    'Casa de Repouso Santa Márcia',
    'rodrigo@gmail.com',
    '123'
);

INSERT INTO PEOPLE_IN_CARE (
    PIC_nursing_home_id,  
    PIC_first_name,
    PIC_last_name, 
    PIC_birth_date,
    PIC_height, 
    PIC_weight, 
    PIC_allergies,
    PIC_notes 
) VALUES 
    (1, 'Maria', 'Silva', '1938-04-10', 1.55, 62, 'Dipirona', NULL),
    (1, 'João', 'Pereira', '1945-11-21', 1.72, 79, NULL, 'Necessita de assistência para locomoção'),
    (1, 'Ana', 'Santos', '1980-07-15', 1.65, 58, 'Amendoim', NULL),
    (1, 'Carlos', 'Oliveira', '2008-09-05', 1.60, 54, NULL, 'Acompanhamento frequente para condição crônica'),
    (1, 'Beatriz', 'Costa', '1952-01-30', 1.60, 67, NULL, NULL);