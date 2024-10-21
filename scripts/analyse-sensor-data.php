<?php
require_once fullPath('vendor/autoload.php');
// require_once fullPath('sendgrid-credentials.php');

// use \SendGrid\Mail\Mail;

if (!isset($_SESSION)) {
    session_start();
}

define('MIN_HEART_RATE', 60);
define('MAX_HEART_RATE', 100);

define('MIN_BODY_TEMPERATURE', 35.5);
define('MAX_BODY_TEMPERATURE', 38);

define('MIN_BLOOD_OXYGEN', 90);

define('MIN_BLOOD_PRESSURE', 85);
define('MAX_BLOOD_PRESSURE', 125);


function analyzeSensorData($timestamp, $sensors)
{
    $alerts = [
        'heart_rate' => ['alert' => false, 'message' => 'OK'],
        'body_temperature' => ['alert' => false, 'message' => 'OK'],
        'blood_oxygen' => ['alert' => false, 'message' => 'OK'],
        'blood_pressure' => ['alert' => false, 'message' => 'OK'],
    ];

    if (intval($sensors['heart_rate']) < MIN_HEART_RATE) {
        $alerts['heart_rate']['alert'] = true;
        $alerts['heart_rate']['message'] = 'MUITO BAIXOS';
    }
    if (intval($sensors['heart_rate']) > MAX_HEART_RATE) {
        $alerts['heart_rate']['alert'] = true;
        $alerts['heart_rate']['message'] = 'MUITO ALTOS';
    }

    if (intval($sensors['body_temperature']) < MIN_BODY_TEMPERATURE) {
        $alerts['body_temperature']['alert'] = true;
        $alerts['body_temperature']['message'] = 'MUITO BAIXA';
    }
    if (intval($sensors['body_temperature']) > MAX_BODY_TEMPERATURE) {
        $alerts['body_temperature']['alert'] = true;
        $alerts['body_temperature']['message'] = 'MUITO ALTA';
    }

    if (intval($sensors['blood_oxygen']) < MIN_BLOOD_OXYGEN) {
        $alerts['blood_oxygen']['alert'] = true;
        $alerts['blood_oxygen']['message'] = 'MUITO BAIXO';
    }

    if (intval($sensors['blood_pressure']) < MIN_BLOOD_PRESSURE) {
        $alerts['blood_pressure']['alert'] = true;
        $alerts['blood_pressure']['message'] = 'MUITO BAIXA';
    }
    if (intval($sensors['blood_pressure']) > MAX_BLOOD_PRESSURE) {
        $alerts['blood_pressure']['alert'] = true;
        $alerts['blood_pressure']['message'] = 'MUITO ALTA';
    }

    foreach ($alerts as $key => $array) {
        if ($array['alert']) {
            // sendAlert($timestamp, $sensors, $alerts);
            return true;
            break;
        }
    }
    return false;
}

function sendAlert($timestamp, $sensors, $alerts)
{
    // The emails should be sent asynchronously maybe with javascript
    // $email = new Mail();
    // $email->setFrom(EMAIL_SENDER, 'Monitoramento de Saúde');
    // $email->setSubject('ALERTA - Sinais Vitais Preocupantes!');
    // $email->addTo($_SESSION['companion_user_email'], '');

    // $formatted_time = DateTimeImmutable::createFromFormat(
    //     'Y-m-d H:i:s',
    //     $timestamp,
    //     new DateTimeZone('America/Sao_Paulo')
    // );

    // $email_content =
    //     "<p> Os sinais vitais de <strong>" .  $_SESSION['user_first_name'] .  $_SESSION['user_last_name'] . "</strong> atingiram níveis preocupantes </p>" .
    //     "<h1> Sinais Vitais: </h1>" .
    //     "<h3>Captura realizada em " . $formatted_time->format("d/m/Y") . ' às ' . $formatted_time->format("H:i:s") . '</h3>' .
    //     "<p><strong>Batimentos Cardíacos: </strong>" . $sensors['heart_rate'] . ' BPM - ' . $alerts['heart_rate']['message'] . "</p>" .
    //     "<p><strong>Temperatura Corporal: </strong>" . $sensors['body_temperature'] . '°C - ' . $alerts['body_temperature']['message'] . "</p>" .
    //     "<p><strong>Oxigênio no Sangue: </strong>" . $sensors['blood_oxygen'] . '% - ' . $alerts['blood_oxygen']['message'] . "</p>" .
    //     "<p><strong>Pressão Sanguínea: </strong>" . $sensors['blood_pressure'] . ' mmHg - ' . $alerts['blood_pressure']['message'] . "</p>";

    // $email->addContent('text/html', $email_content);

    // $sendgrid = new \SendGrid(SENDGRID_API_KEY);
    // try {
    //     $response = $sendgrid->send($email);
    // } catch (Exception $e) {
    //     echo 'Caught exception: ' . $e->getMessage() . "\n";
    // }
}
