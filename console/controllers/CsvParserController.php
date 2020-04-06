<?php
namespace console\controllers;

use console\models\ParseCsv;

class CsvParserController extends \yii\console\Controller
{
    const PATH = '././src/data/';
    // список файлов для обработки
    const DATA = [
        'categories.csv',
        'cities.csv',
        'users.csv',
        'tasks.csv',
        'accounts.csv',
        'responds.csv',
        'reviews.csv'
    ];

    public function actionIndex()
    {
        try {
            foreach (self::DATA as $file) {
                $fh = new ParseCsv(self::PATH . $file);
                echo 'успешно сформирован ' . $fh->csvToSQL() . "\n";
            }
        } catch (\Exception $e) {
            echo 'Ошибка: '.$e->getMessage();
        }
    }
}
