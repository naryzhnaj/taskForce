<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use TaskForse\CsvToSQL;
require_once 'vendor/autoload.php';

try {
    CsvToSQL::parseCsv('data\categories.csv', 'categories');
    CsvToSQL::parseCsv('data\cities.csv', 'cities');
    CsvToSQL::parseCsv('data\users.csv', 'users');
    CsvToSQL::parseCsv('data\profiles.csv', 'accounts');
    CsvToSQL::parseCsv('data\tasks.csv', 'tasks');
    CsvToSQL::parseCsv('data\replies.csv', 'responds');
    CsvToSQL::parseCsv('data\opinions.csv', 'reviews');
} catch (RuntimeException $e) {
    echo 'Ошибка: '.$e->getMessage();
} catch (Error $e) {
    echo 'Ошибка: '.$e->getMessage();
}
