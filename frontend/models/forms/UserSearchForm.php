<?php

namespace frontend\models\forms;

use yii\base\Model;
use yii\db\Query;
use frontend\models\Tasks;
use frontend\models\Users;

/**
 * This is the form class for executor search.
 *
 * @var bool $isFree
 * @var bool $isOnline
 * @var bool $withReviews
 * @var bool $isFavorite
 * @var string $name
 * @var int[] $categories
 */
class UserSearchForm extends Model
{
    public $isFree;
    public $isOnline;
    public $withReviews;
    public $isFavorite;
    public $categories;
    public $name;

    public function rules()
    {
        return [
            [['isFree', 'isOnline', 'withReviews', 'isFavorite'], 'boolean'],
            [['isFree', 'isOnline', 'withReviews', 'isFavorite'], 'default', 'value' => false],
            ['categories', 'default', 'value' => []],
            ['name', 'string'],
            ['name', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'isFree' => 'Сейчас свободен',
            'isOnline' => 'Сейчас онлайн',
            'withReviews' => 'Есть отзывы',
            'isFavorite' => 'В избранном',
        ];
    }

    /**
     * добавляет к запросу условия в зависимости от выбранных полей
     *
     * @return ActiveQuery $query
     */
    public function search()
    {
        $query = Users::find()->innerJoinWith('specialization')->indexBy('id');

        if ($this->name) {
            return $query->andWhere(['like', 'name', $this->name]);
        }
        if ($this->isFavorite) {
            $query->innerJoin('favorites f', 'f.favorite_id=users.id')->where(['f.user_id' => \Yii::$app->user->id]);
        }
        if ($this->withReviews) {
            $query->innerJoinWith('reviews');
        }
        if ($this->isFree) {
            $busyQuery = (new Query())->select('executor_id AS id')->from('tasks')->where(['status' => Tasks::STATUS_PROGRESS]);
            $query->andWhere(['not in', 'users.id', $busyQuery ]);
        }
        if ($this->categories) {
            $query->andWhere(['in', 'category_id', $this->categories]);
        }

        return $query->distinct();
    }
}
