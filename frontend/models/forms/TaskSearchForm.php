<?php

namespace frontend\models\forms;

use yii\base\Model;
use frontend\models\Tasks;

/**
 * This is the form class for task search.
 *
 * @var string $period
 * @var bool $withoutResponds
 * @var bool $isDistant
 * @var string $title
 * @var int[] $categories
 */
class TaskSearchForm extends Model
{
    public $period;
    public $withoutResponds;
    public $isDistant;
    public $categories;
    public $title;

    public function rules()
    {
        return [
            [['withoutResponds', 'isDistant'], 'boolean'],
            [['withoutResponds', 'isDistant'], 'default', 'value' => false],

            ['categories', 'default', 'value' => []],
            ['period', 'in', 'range' => ['all', 'day', 'week', 'month']],
            ['title', 'string'],
            ['title', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'withoutResponds' => 'Без откликов',
            'isDistant' => 'Удаленная работа',
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
            case 'day': $term = '24 HOUR'; break;
            case 'week': $term = '7 DAY'; break;
            case 'month': $term = '30 DAY';
        }

        return 'DATE_SUB(NOW(), INTERVAL ' . $term .')';
    }

    /**
     * добавляет к запросу условия поиска
     *
     * @return ActiveQueryQuery $query
     */
    public function search()
    {
        $query = Tasks::getMainList();
        $query->andFilterWhere(['like', 'title', $this->title]);

        if ($this->isDistant) {
            $query->andWhere('address IS NULL');
        }
        if (in_array($this->period, ['day', 'week', 'month'])) {
            $query->andWhere('dt_add >=' . $this->interval);
        }
        if ($this->categories) {
            $query->andWhere(['in', 'category_id', $this->categories]);
        }
        if ($this->withoutResponds) {
            $query->joinWith('responds')->where('task_id IS NULL');
        }
        return $query;
    }
}
