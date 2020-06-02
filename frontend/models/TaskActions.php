<?php
namespace frontend\models;

use frontend\models\Responds;
use frontend\models\Reviews;
use frontend\models\Users;
use yii\web\ServerErrorHttpException;

/**
 * бизнес-логика для сущности Задание
 *
 * @var Tasks $model объект, над которым действия совершаются
 * @var int $user_id ид текущего пользователя
 */
class TaskActions
{
    public const STATUS_NEW = 'new';
    public const STATUS_PROGRESS = 'in_progress';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAIL = 'fail';

    public const ACTION_CANCEL = 'cancel';
    public const ACTION_COMPLETE = 'complete';
    public const ACTION_REFUSE = 'refuse';
    public const ACTION_RESPOND = 'respond';

    public const CUSTOMER = 'customer';
    public const EXECUTOR = 'executor';
    public const VISITOR = 'visitor';

    private $model;
    private $user_id;

    public function __construct(Tasks $data, int $id)
    {
        $this->model = $data;
        $this->user_id = $id;
    }

    /**
     * определение роли активного пользователя по id.
     *
     * @return string роль активного пользователя
     */
    private function getRole()
    {
        if ($this->user_id === $this->model->author_id) {
            return self::CUSTOMER;
        }
        if ($this->user_id === $this->model->executor_id) {
            return self::EXECUTOR;
        }

        return self::VISITOR;
    }

    /**
     * определение списка доступных пользователю действий.
     *
     * @return string
     */
    public function getActionList()
    {
        $role = $this->getRole();
        switch ($this->model->status) {
            case self::STATUS_PROGRESS:
                if ($role === self::EXECUTOR) {
                    return self::ACTION_REFUSE;
                }
                if ($role === self::CUSTOMER) {
                    return self::ACTION_COMPLETE;
                }

            case self::STATUS_NEW:
                // откликаться может только исполнитель и только один раз
                if ($role === self::VISITOR && Users::isUserDoer($this->user_id)
                    && !$this->model->checkCandidate($this->user_id)) {
                    return self::ACTION_RESPOND;
                }
                if ($role === self::CUSTOMER) {
                    return self::ACTION_CANCEL;
                }
        }
        return '';
    }

    /**
     * одобрить отклик.
     *
     * @param Responds $respond
     *
     * @throws ServerErrorHttpException
     *
     * @return mixed
     */
    public function admitRespond($respond)
    {
        if ($this->getRole() !== self::CUSTOMER) {
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $respond->status = self::STATUS_PROGRESS;
            ++$respond->author->orders;
            $this->model->status = self::STATUS_PROGRESS;
            $this->model->executor_id = $respond->author_id;
            $this->model->save();
            $respond->save();
            $respond->author->save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ServerErrorHttpException('при сохранении произошла ошибка');
        }
    }

    /**
     * отклонить отклик.
     *
     * @param Responds $respond
     *
     * @return mixed
     */
    public function refuseRespond($respond)
    {
        if ($this->getRole() !== self::CUSTOMER) {
            return false;
        }
        $respond->status = self::STATUS_CANCEL;
        $respond->save();
    }

    /**
     *  исполнитель отказывается.
     *
     *  @return mixed
     */
    public function refuse()
    {
        if ($this->getRole() !== self::EXECUTOR) {
            return false;
        }
        $this->fail();
    }

    /** поменять статус задания на проваленное */
    private function fail(): void
    {
        $this->model->status = self::STATUS_FAIL;
        ++$this->model->executor->failures;
        $this->model->executor->save();
        $this->model->save();
    }

    /**
     *  заказчик удаляет задание.
     *
     * @return mixed
     */
    public function cancelTask()
    {
        if ($this->getRole() !== self::CUSTOMER) {
            return false;
        }
        $this->model->status = self::STATUS_CANCEL;
        $this->model->save();
    }

    /**
     * заказчик завершает задание.
     *
     * @param array $data данные отзыва
     *
     * @throws ServerErrorHttpException
     *
     * @return mixed
     */
    public function complete($data)
    {
        if ($this->getRole() !== self::CUSTOMER) {
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $review = new Reviews();
            $review->task_id = $this->model->id;
            $review->user_id = $this->model->executor_id;
            $review->value = $data->mark;
            $review->comment = $data->comment;
            $review->save();
            // проверка успешности
            if ($data->answer) {
                $this->model->status = self::STATUS_COMPLETED;
                $this->model->save();
            } else {
                $this->fail();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ServerErrorHttpException('при сохранении произошла ошибка');
        }
    }

    /**
     * гость откликается.
     *
     * @param $data данные отклика
     *
     * @return mixed
     */
    public function respond($data)
    {
        if ($this->getRole() !== self::VISITOR || $this->model->checkCandidate($this->user_id)) {
            return false;
        }
        $respond = new Responds();
        $respond->task_id = $this->model->id;
        $respond->author_id = $this->user_id;
        $respond->price = $data->price;
        $respond->comment = $data->comment;
        $respond->save();
    }
}
