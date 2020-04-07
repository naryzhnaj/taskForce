<?php
namespace console\controllers;

use console\models\ParseCsv;

class CsvParserController extends \yii\console\Controller
{
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
                $path = sprintf('%s/%s', \Yii::getAlias('@csvPath'), $file);
                $fh = new ParseCsv($path);
                $this->stdout('успешно сформирован ' . $fh->csvToSQL() . "\n");
            }
        } catch (\Exception $e) {
            echo 'Ошибка: '.$e->getMessage();
        }
    }
}
