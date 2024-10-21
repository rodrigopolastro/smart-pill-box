<?php

function fullPath($file_path = "")
{
    $full_path =
        $_SERVER['DOCUMENT_ROOT'] . '/' .
        'pi3-smart-pill-box' . '/' .
        $file_path;

    return $full_path;
}
