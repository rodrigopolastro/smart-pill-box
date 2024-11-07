<?php

function calcAge($birthDate)
{
    $currentDate = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));
    $birthDate = new DateTimeImmutable($birthDate);
    $datesDifference = date_diff($currentDate, $birthDate);
    $age = $datesDifference->format('%y');

    return $age;
}
