<?php

namespace frontend\models\forms;

use yii\base\Model;
use yii\db\Query;

/**
 * This is the form class for executor search.
 *
 * @var bool $is_free
 * @var bool $is_online
 * @var bool $with_reviews
 * @var bool $is_favorite
 * @var string $name
 * @var int[] $categories
 */
class UserSearchForm extends Model
{
    public $is_free;
    public $is_online;
    public $with_reviews;
    public $is_favorite;
    public $categories;
    public $name;

    public function rules()
    {
        return [
            [['is_free', 'is_online', 'with_reviews', 'is_favorite'], 'boolean'],
            [['is_free', 'is_online', 'with_reviews', 'is_favorite'], 'default', 'value' => false],
            ['categories', 'default', 'value' => []],
            ['name', 'string'],
            ['name', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'is_free' => 'Сейчас свободен',
            'is_online' => 'Сейчас онлайн',
            'with_reviews' => 'Есть отзывы',
            'is_favorite' => 'В избранном',
        ];
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
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andWhere([($this->is_favorite) ? 'in' : 'not in', 'users.id',
            (new Query())->select('f.favorite_id')->from('favorites f')->where(['f.user_id' => \Yii::$app->user->id])]);

        $query->andWhere([($this->is_free) ? 'not in' : 'in', 'users.id', \frontend\models\Tasks::getBusyDoers()]);

        if ($this->categories) {
            $query->andWhere(['in', 'users.id',
            (new Query())->select('s.user_id')->from('specialization s')->where(['in', 'category_id', $this->categories]), ]);
        }
        $query->andWhere([($this->with_reviews) ? 'in' : 'not in', 'users.id', (new Query())->select('r.user_id')->from('reviews r')]);
        return $query;
    }
}
