<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForse\ParseCsv;
require_once 'vendor/autoload.php';

try {
    $file = new ParseCsv('data\categories.csv');
    $file->csvToSQL('categories');

    $file = new ParseCsv('data\cities.csv');
    $file->csvToSQL('cities');

    $file = new ParseCsv('data\users.csv');
    $file->csvToSQL('users');

    $file = new ParseCsv('data\tasks.csv');
    $file->csvToSQL('tasks');

    $file = new ParseCsv('data\profiles.csv');
    $file->csvToSQL('accounts');

    $file = new ParseCsv('data\replies.csv');
    $file->csvToSQL('responds');

    $file = new ParseCsv('data\opinions.csv');
    $file->csvToSQL('reviews');
} catch (Exception $e) {
    echo 'Ошибка: '.$e->getMessage();
} catch (Error $e) {
    echo 'Ошибка: '.$e->getMessage();
}
