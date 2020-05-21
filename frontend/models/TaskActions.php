<?php
namespace frontend\models;

use frontend\models\Responds;
use frontend\models\Reviews;
use frontend\models\Users;

class TaskActions
{
    public const STATUS_NEW = 'new';
    public const STATUS_PROGRESS = 'in_progress';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAIL = 'fail';

    public const CUSTOMER = 'customer';
    public const EXECUTOR = 'executor';
    public const VISITOR = 'visitor';

    private $model;
    private $user_id;

    /**
     * TaskActions constructor.
     *
     * @param ActiveRecord $data текущее задание
     * @param int          $id   id активного пользователя
     */
    public function __construct(Tasks $data, int $id)
    {
        $this->model = $data;
        $this->user_id = $id;
    }

    /**
     * определение роли активного пользователя по id.
     *
     * @return string $role роль активного пользователя
     */
    private function getRole()
    {
        $role = self::VISITOR;

        if ($this->user_id === $this->model->author_id) {
            $role = self::CUSTOMER;
        } elseif ($this->user_id === $this->model->executor_id) {
            $role = self::EXECUTOR;
        }
        return $role;
    }

    /**
     * определение карты переходов статуса задачи в зависимости от роли, текущего статуса и действий.
     *
     * @return array $res доступные действия и соответсвующие им переходы состояния
     */
    private function getOperations()
    {
        $res = [];
        $role = $this->getRole();

        switch ($this->model->status) {
            case self::STATUS_PROGRESS:
                switch ($role) {
                    case self::EXECUTOR:
                        $res = ['refuse' => self::STATUS_FAIL]; break;
                    case self::CUSTOMER:
                        $res = ['complete' => self::STATUS_COMPLETED];
                } break;

            case self::STATUS_NEW:
                switch ($role) {
                    case self::VISITOR:
                        // откликаться может только исполнитель и только один раз
                        $res = (Users::isUserDoer($this->user_id) && !$this->model->checkCandidate($this->user_id)) ? ['respond' => null] : []; break;

                    case self::CUSTOMER:
                        $res = ['cancel' => self::STATUS_CANCEL];
                }
        }

        return $res;
    }

    /**
     * определение списка доступных пользователю действий.
     *
     * @return array
     */
    public function getActionList()
    {
        return array_keys($this->getOperations()) ?? [];
    }

    /**
     * одобрить отклик.
     *
     * @param аctiveRecord $respond
     */
    public function admitRespond($respond): void
    {
        $respond->status = self::STATUS_PROGRESS;
        $respond->author->orders++;
        $this->model->status = self::STATUS_PROGRESS;
        $this->model->executor_id = $respond->author_id;
        $this->model->save();
        $respond->save();
    }

    /**
     * отклонить отклик.
     *
     * @param аctiveRecord $respond
     */
    public function refuseRespond($respond): void
    {
        $respond->status = self::STATUS_CANCEL;
        $respond->save();
    }

    /**
     *  исполнитель отказывается.
     */
    public function refuse(): void
    {
        $this->model->status = self::STATUS_FAIL;
        $this->model->executor->failures++;
        $this->model->save();
    }

    /**
     *  заказчик удаляет задание.
     */
    public function cancelTask(): void
    {
        $this->model->status = self::STATUS_CANCEL;
        $this->model->save();
    }

    /**
     * заказчик завершает задание.
     *
     * @param array $data данные отзыва
     */
    public function complete($data): void
    {
        $review = new Reviews();
        $review->task_id = $this->model->id;
        $review->user_id = $this->model->executor_id;
        $review->value = $data->value;
        $review->comment = $data->comment;
        $review->save();
        // конечный статус
        if ($data->answer) {
            $this->model->status = self::STATUS_COMPLETED;
            $this->model->save();
        } else $this->refuse();
    }

    /**
     * гость откликается.
     *
     * @param $data данные отклика
     * @param $user_id id соискателя
     */
    public function respond($data, $user_id): void
    {
        $respond = new Responds();
        $respond->task_id = $this->model->id;
        $respond->author_id = $user_id;
        $respond->price = $data->price;
        $respond->comment = $data->comment;
        $respond->save();
    }
}
