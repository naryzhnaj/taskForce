<?php

namespace frontend\models\forms;

use yii\base\Model;
use yii\db\Query;
use frontend\models\Tasks;
use frontend\models\Users;

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
     * @return ActiveQuery $query
     */
    public function search()
    {
        $subQuery = (new Query())->select('user_id AS id')->from('specialization')->distinct();
        $query = Users::find()->where(['id'=> $subQuery])->indexBy('id');

        if ($this->name) {
            return $query->andWhere(['like', 'name', $this->name]);
        }
        if ($this->is_favorite) {
            $query->andWhere(['id' => \Yii::$app->user->identity->favoriteList]);
        }
        if ($this->is_free) {
            $query->andWhere(['id' => Tasks::getFreeDoers()]);
        }
        if (($this->categories)) {
            $query->andWhere(['id' =>
                (new Query())->select('user_id AS id')->from('specialization')->where(['in', 'category_id', $this->categories])]);
        }
        if ($this->with_reviews) {
            $query->andWhere(['id' => (new Query())->select('user_id AS id')->from('reviews')]);
        }
          
        return $query;
    }
}
