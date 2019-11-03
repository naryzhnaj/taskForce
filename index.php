<?php

declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForse\ParseCsv;

require_once 'vendor/autoload.php';
$source = ['data\categories.csv',
            'data\cities.csv',
            'data\users.csv',
            'data\tasks.csv',
            'data\accounts.csv',
            'data\responds.csv',
            'data\reviews.csv', ];

try {
    foreach ($source as $file) {
        $fh = new ParseCsv($file);
        echo 'успешно сформирован '.$fh->csvToSQL().'<br>';
    }
} catch (Exception $e) {
    echo 'Ошибка: '.$e->getMessage();
} catch (Error $e) {
    echo 'Ошибка: '.$e->getMessage();
}
