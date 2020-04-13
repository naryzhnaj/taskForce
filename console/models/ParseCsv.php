<?php

namespace console\models;

class ParseCsv
{
    /**
     * ParseCsv constructor.
     *
     * @param string $path исходный файл
     *
     * @throws Exception
     */
    public function __construct(string $path)
    {
        $this->fh = new \SplFileObject($path, 'r');
        if ($this->fh->eof()) {
            throw new \Exception('empty file');
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
     * @return string $file_name имя искомого файла
     */
    public function csvToSQL(): string
    {
        // соответствующее имя таблицы в БД
        $table = $this->fh->getBasename('.csv');
        // соответствующее имя sql файла
        $file_name = str_replace('.csv', '.sql', $this->fh->getPathname());
        $sql_file = new \SplFileObject($file_name, 'w');

        // записываем заголовок
        $columns = implode(', ', $this->fh->fgetcsv());
        $sql_file->fwrite("INSERT INTO $table ($columns) VALUES \n");

        // записываем первую строку с данными
        $data = $this->fh->fgetcsv();
        $sql_file->fwrite($this->parseValues($data));

        while ($data = $this->fh->fgetcsv()) {
            $sql_file->fwrite(",\n".$this->parseValues($data));
        }
        $sql_file->fwrite(';');

        return $file_name;
    }
}
