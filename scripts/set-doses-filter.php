<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';

//Clear filters if user came from another page
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF']) == false) {
    $_SESSION['doses_filters'] = [];
    $_SESSION['doses_selected_filter'] = null;
    $_SESSION['filtered_value'] = null;
} else if (isset($_POST['selected_filter'])) {
    $_SESSION['doses_filters'] = [];
    $_SESSION['filtered_value'] = null;
    $_SESSION['doses_selected_filter'] = $_POST['selected_filter'];
    if ($_POST['selected_filter'] == 'person_in_care') {
        $_SESSION['doses_filters'][] = ['PIC_id', $_POST['person_in_care_id']];
        $_SESSION['filtered_value'] = intval($_POST['person_in_care_id']);
    } elseif ($_POST['selected_filter'] == 'medicine') {
        $_SESSION['doses_filters'][] = ['MED_id', $_POST['medicine_id']];
        $_SESSION['filtered_value'] = $_POST['medicine_id'];
    } elseif ($_POST['selected_filter'] == 'due_date') {
        $_SESSION['doses_filters'][] = ['DOS_due_datetime', $_POST['due_date']];
        $_SESSION['filtered_value'] = $_POST['due_date'];
    }
    //Reload page to $_POST paramters
    header('Location: ' . fullPath('views/pages/doses.php', true));
    exit();
}
