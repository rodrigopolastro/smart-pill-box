<?php
// require '../sendgrid-credentials.php'; #
require '../vendor/autoload.php';

use \SendGrid\Mail\Mail;

if (!isset($_SESSION)) {
    session_start();
}

function scheduleDoseReminder($dose)
{
    // The emails should be sent asynchronously maybe with javascript
    // $sendgrid = new \SendGrid(SENDGRID_API_KEY);
    // $dose_time = DateTimeImmutable::createFromFormat(
    //     'Y-m-d H:i',
    //     $dose['due_date'] . ' ' . $dose['due_time'],
    //     new DateTimeZone('America/Sao_Paulo')
    // );

    // $email_content = "
    //     <h1> Chegou a hora de tomar sua dose! </h1>
    //     <h3>Medicamento:" . $dose['medicine_name'] . "</h3> 
    //     <h3>Horário: " . $dose_time->format("d/m/Y") . ' às ' . $dose['due_time'] . '</h3>
    //     <p>Não desista, continue firme!</p>';
    // sendToMonitoredUser($sendgrid, $dose_time, $email_content);

    // $email_content = "
    //     <h1>" . $_SESSION['user_first_name'] . $_SESSION['user_last_name'] . "deve tomar uma dose agora! </h1>
    //     <h3>Medicamento:" . $dose['medicine_name'] . "</h3> 
    //     <h3>Horário: " . $dose_time->format("d/m/Y") . ' às ' . $dose['due_time'] . '</h3>
    //     <p>Continue acompanhando a saúde de quem você ama conosco!</p>';
    // sendToCompanionUser($sendgrid, $dose_time, $email_content);
}

function sendToMonitoredUser($sendgrid, $dose_time, $email_content)
{
    // $email = new Mail();
    // $email->setFrom(EMAIL_SENDER, 'Monitoramento de Saúde');
    // $email->setSubject('Você tem uma dose de medicamento para tomar!');
    // $email->addTo($_SESSION['user_email'], 'Example Recipient');
    // $email->addContent('text/html', $email_content);

    // $email->setSendAt($dose_time->getTimestamp());

    // try {
    //     $response = $sendgrid->send($email);
    // } catch (Exception $e) {
    //     echo 'Caught exception: ' . $e->getMessage() . "\n";
    // }
}

function sendToCompanionUser($sendgrid, $dose_time, $email_content)
{
    // $email = new Mail();
    // $email->setFrom(EMAIL_SENDER, 'Monitoramento de Saúde');
    // $email->setSubject(
    //     $_SESSION['user_first_name'] . $_SESSION['user_last_name'] .
    //         " recebeu um lembrete para tomar uma dose!"
    // );
    // $email->addTo($_SESSION['companion_user_email'], '');
    // $email->addContent('text/html', $email_content);
    // $email->setSendAt($dose_time->getTimestamp());


    // try {
    //     $response = $sendgrid->send($email);
    // } catch (Exception $e) {
    //     echo 'Caught exception: ' . $e->getMessage() . "\n";
    // }
}
