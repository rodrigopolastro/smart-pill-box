<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/nursing-homes.php');

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['nursing_homes_action'])) {
    $params = $_POST ?? [];
    nursingHomesController($params['nursing_homes_action'], $params);
}

function nursingHomesController($nursingHomesAction, $params = [])
{
    switch ($nursingHomesAction) {
        case 'sign_up':
            $nursing_home = getNursingHomeByEmail($params['email']);
            if ($nursing_home) {
                $query_string = '?sign_up_status=already_registered';
                header("Location: /smart-pill-box/views/pages/sign-up.php" . $query_string);
                exit();
            } else {
                $nursing_home = [
                    'email'    => $params['email'],
                    'password' => $params['password'],
                    'company_name' => $params['company_name']
                ];
                try {
                    $nursingHomeId = createNursingHome($nursing_home);
                    $_SESSION['logged_nursing_home_id'] = $nursingHomeId;
                    $_SESSION['logged_nursing_home_email'] = $nursing_home['email'];
                    $_SESSION['logged_nursing_home_password'] = $nursing_home['password'];
                    $_SESSION['logged_nursing_home_company_name'] = $nursing_home['company_name'];
                    header("Location: /smart-pill-box/views/pages/overview.php");
                    exit();
                } catch (PDOException $exception) {
                    echo $exception->getMessage();
                }
            }
            break;

        case 'login':
            $nursing_home = getNursingHomeByEmail($params['email']);
            print_r($nursing_home);
            print_r($params);
            if ($nursing_home && $nursing_home['NSH_password'] == trim($params['password'])) {
                $_SESSION['logged_nursing_home_id'] = $nursing_home['NSH_id'];
                $_SESSION['logged_nursing_home_email'] = $nursing_home['NSH_email'];
                $_SESSION['logged_nursing_home_password'] = $nursing_home['NSH_password'];
                $_SESSION['logged_nursing_home_company_name'] = $nursing_home['NSH_company_name'];
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
