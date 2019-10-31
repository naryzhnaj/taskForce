<?php

declare(strict_types=1);

namespace TaskForse;

use TaskForse\Ex\FileFormatException;

class ParseCsv
{
    /**
     * ParseCsv constructor.
     *
     * @param string $path исходный файл
     *
     * @throws FileFormatException
     */
    public function __construct(string $path)
    {
        $this->fh = new \SplFileObject($path, 'r');
        if ($this->fh->eof()) {
            throw new FileFormatException('empty file');
        }
        $this->fh->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD);
    }

    /**
     * в SQL файле у текстовых значений должны быть кавычки.
     *
     * @param array $data текущая строка из файла
     *
     * @return $line готовая для вставки строка со списком значений
     */
    private function parseValues(array $data): string
    {
        $line = '('.implode(', ', array_map(function ($field) {
            return is_numeric($field) ? $field : "'$field'";
        }, $data)).')';

        return $line;
    }

    /**
     * перевод данных из CSV в SQL формат
     * в результате создается SQL файл с запросами.
     *
     * @param string $table имя таблицы в БД
     */
    public function csvToSQL(string $table): void
    {
        $sql_file = new \SplFileObject("$table.sql", 'w');

        $columns = implode(', ', $this->fh->fgetcsv());

        while ($data = $this->fh->fgetcsv()) {
            $line = 'INSERT INTO ' . $table . ' (' . $columns . ') VALUES '. $this->parseValues($data) . ";\n";
            $sql_file->fwrite($line);
        }
    }
}
