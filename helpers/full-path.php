<?php

function fullPath($file_path = "", $useWebPath = false)
{
    if ($useWebPath) {
        $root = '/'; #localhost/
    } else {
        $root = $_SERVER['DOCUMENT_ROOT'] . '/';
    }

    $full_path =
        $root .
        'smart-pill-box' . '/' .
        $file_path;

    return $full_path;
}
