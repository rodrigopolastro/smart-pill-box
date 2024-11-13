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
            $nursingHome = selectNursingHomeByEmail($params['email']);
            if ($nursingHome) {
                $queryString = '?sign_up_status=already_registered';
                header("Location: /smart-pill-box/views/pages/sign-up.php" . $queryString);
                exit();
            } else {
                $nursingHome = [
                    'email'    => $params['email'],
                    'password' => $params['password'],
                    'company_name' => $params['company_name']
                ];
                try {
                    $nursingHomeId = insertNursingHome($nursingHome);
                    $_SESSION['logged_nursing_home_id'] = $nursingHomeId;
                    $_SESSION['logged_nursing_home_email'] = $nursingHome['email'];
                    $_SESSION['logged_nursing_home_password'] = $nursingHome['password'];
                    $_SESSION['logged_nursing_home_company_name'] = $nursingHome['company_name'];
                    header("Location: /smart-pill-box/views/pages/overview.php");
                    exit();
                } catch (PDOException $exception) {
                    echo $exception->getMessage();
                }
            }
            break;

        case 'login':
            $nursingHome = selectNursingHomeByEmail($params['email']);
            print_r($nursingHome);
            print_r($params);
            if ($nursingHome && $nursingHome['NSH_password'] == trim($params['password'])) {
                $_SESSION['logged_nursing_home_id'] = $nursingHome['NSH_id'];
                $_SESSION['logged_nursing_home_email'] = $nursingHome['NSH_email'];
                $_SESSION['logged_nursing_home_password'] = $nursingHome['NSH_password'];
                $_SESSION['logged_nursing_home_company_name'] = $nursingHome['NSH_company_name'];
                header('Location: /smart-pill-box/views/pages/overview.php');
                exit();
            } else {
                $queryString = '?login_status=incorrect_info';
                header('Location: /smart-pill-box/views/pages/login.php' . $queryString);
            }
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
