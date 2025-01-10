<?php
declare(strict_types=1);
spl_autoload_register(function ($className) {

    $file = str_replace('\\', '/', $className) . '.php';

    if (file_exists('business/' . $file)) {
        require ('business/' . $file);
    }
    if (file_exists('data/' . $file)) {
        require ('data/' . $file);
    }
    if (file_exists('entities/' . $file)) {
        require ('entities/' . $file);
    }
    if (file_exists('presentation/' . $file)) {
        require ('presentation/' . $file);
    }
});