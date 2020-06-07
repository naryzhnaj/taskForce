<?php

namespace frontend\models\forms;

use yii\base\Model;

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
            ['without_responds', 'default', 'value' => false],
            ['is_distant', 'default', 'value' => false],
            ['categories', 'default', 'value' => []],
            ['period', 'in', 'range' => ['day', 'week', 'month']],
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
            case 'day': $term = '24 hour'; break;
            case 'week': $term = '7 day'; break;
            case 'month': $term = '30 day';
        }

        return 'INTERVAL '.$term;
    }

    /**
     * добавляет к запросу условия в зависимости от выбранных полей
     *
     * @param ActiveQuery $startQuery
     * @return ActiveQuery $query
     */
    public function search($startQuery)
    {
        $query = clone $startQuery;
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andWhere(($this->is_distant) ? 'address IS NULL' : 'address IS NOT NULL');

        if ($this->period != 'all') {
            $query->andWhere('dt_add >= DATE_SUB(now(),'.$this->interval.')');
        }

        if ($this->categories) {
            $query->andWhere(['category_id' => $this->categories]);
        }

        if ($this->without_responds) {
            $query->joinWith('responds')->where('responds.task_id is NULL');
        }
        return $query;
    }
}
