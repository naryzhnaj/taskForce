<?php

namespace frontend\models\forms;

use yii\base\Model;
use yii\db\Query;

/**
 * This is the form class for task search.
 *
 * @var string $period
 * @var bool $without_responds
 * @var bool $is_distant
 * @var string $title
 * @var int[] $categories
 */
class TaskSearchForm extends Model
{
    public $period;
    public $without_responds;
    public $is_distant;
    public $categories;
    public $title;

    public function rules()
    {
        return [
            [['without_responds', 'is_distant'], 'boolean'],
            [['without_responds', 'is_distant'], 'default', 'value' => false],

            ['categories', 'default', 'value' => []],
            ['period', 'in', 'range' => ['all', 'day', 'week', 'month']],
            ['title', 'string'],
            ['title', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'without_responds' => 'Без откликов',
            'is_distant' => 'Удаленная работа',
        ];
    }

    /**
     * расшифровка выбранного периода
     *
     * @return string
     */
    public function getInterval()
    {
        switch ($this->period) {
            case 'all':
            case 'day': $term = '24 HOUR'; break;
            case 'week': $term = '7 DAY'; break;
            case 'month': $term = '30 DAY';
        }

        return 'DATE_SUB(NOW(), INTERVAL ' . $term .')';
    }

    /**
     * добавляет к запросу условия поиска
     *
     * @param ActiveQueryQuery $startQuery
     * @return ActiveQueryQuery $query
     */
    public function search($startQuery)
    {
        $query = clone $startQuery;

        $query->andFilterWhere(['like', 'title', $this->title]);
        
        if ($this->is_distant) {
            $query->andWhere('address IS NULL');
        }
        if ($this->period != 'all') {
            $query->andWhere('dt_add >=' . $this->interval);
        }
        if (count($this->categories)) {
           $query->andWhere(['in', 'category_id', $this->categories]);
        }

        if ($this->without_responds) {
            $query->andWhere(['not in', 'id', (new Query())->select('task_id')->from('responds')->distinct()->column()]);
        }
        return $query;
    }
}
