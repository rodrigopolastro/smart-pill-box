<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/nursing-homes.php');

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['nursing_homes_action'])) {
    $params = $_POST['params'] ?? [];
    nursingHomesController($_POST['nursing_homes_action'], $params);
}

function nursingHomesController($nursingHomesAction, $params = [])
{
    switch ($nursingHomesAction) {
        case 'sign_up':
            $nursing_home = getNursingHomeByEmail($_POST['email']);
            if ($nursing_home) {
                $query_string = '?sign_up_status=already_registered';
                header("Location: /smart-pill-box/views/pages/sign-up.php" . $query_string);
                exit();
            } else {
                $nursing_home = [
                    'email'    => $_POST['email'],
                    'password' => $_POST['password'],
                    'company_name' => $_POST['company_name']
                ];
                try {
                    $nursing_home['id'] = createNursingHome($nursing_home);
                    $_SESSION['logged_nursing_home'] = $nursing_home;
                } catch (PDOException $exception) {
                    echo $exception->getMessage();
                }

                header("Location: /smart-pill-box/views/pages/list-medicines.php");
                exit();
            }
            break;

        case 'login':
            $nursing_home = getNursingHomeByEmail($_POST['email']);
            print_r($nursing_home);
            print_r($_POST);
            if ($nursing_home && $nursing_home['NSH_password'] == trim($_POST['password'])) {
                $_SESSION['logged_nursing_home'] = $nursing_home;
                header('Location: /smart-pill-box/views/pages/overview.php');
                exit();
            } else {
                $query_string = '?login_status=incorrect_info';
                header('Location: /smart-pill-box/views/pages/login.php' . $query_string);
            }
            print_r($_SESSION);
            break;

        case 'logout':
            session_unset();
            session_destroy();
            header('Location: /smart-pill-box/views/pages/login.php');
            exit();
            break;

        default:
            return [
                'sucesso' => false,
                'msgErro' => "Ação para Casas de Repouso inválida informada: '" . $nursingHomesAction . "'"
            ];
    }
}
