<?php

namespace frontend\models;

use yii\web\ServerErrorHttpException;
use yii\web\ForbiddenHttpException;
use frontend\models\forms\RespondForm;

/**
 * бизнес-логика для сущности Задание.
 *
 * @var $model Tasks объект, над которым действия совершаются
 * @var $user_id int ид текущего пользователя
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
    private function getRole(): string
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
     * определение доступного пользователю действия.
     *
     * @return string
     */
    public function getActionList(): string
    {
        $role = $this->getRole();
        if (!$this->isUserAllowedToRespond() || !in_array($this->model->status, [self::STATUS_NEW, self::STATUS_PROGRESS])) {
            return '';
        }
        if ($this->model->status === self::STATUS_PROGRESS) {
            return ($role === self::EXECUTOR) ? self::ACTION_REFUSE : self::ACTION_COMPLETE;
        }
        return ($role === self::CUSTOMER) ? self::ACTION_CANCEL : self::ACTION_RESPOND;
    }
    
    /**
     * проверка права оставлять отклик на задание
     *
     * @return bool
     */
    private function isUserAllowedToRespond(): bool
    {
        return ($this->model->status === self::STATUS_NEW && 
            $this->getRole() === self::VISITOR &&
            $this->model->checkCandidate($this->user_id));
    }

    /**
     * проверка права просматривать стр.описания задания
     *
     * @return bool
     */
    public function isUserAllowedToView(): bool
    {
        return ($this->model->status === self::STATUS_NEW || in_array($this->getRole(), [self::CUSTOMER, self::EXECUTOR]));
    }

    /**
     * заказчик одобряет отклик.
     *
     * @param Responds $respond
     *
     * @throws ServerErrorHttpException
     *
     * @return void
     */
    public function admitRespond(Responds $respond): void
    {
        if ($this->getRole() !== self::CUSTOMER || $this->model->status !== self::STATUS_NEW) {
            throw new ForbiddenHttpException('Извините, действие недоступно');
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $respond->updateAttributes(['status' => self::STATUS_PROGRESS]);
            $this->model->status = self::STATUS_PROGRESS;
            $this->model->executor_id = $respond->author_id;
            if (!$this->model->save()) {
                throw new \Exception('Не удалось сохранить');
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ServerErrorHttpException('при сохранении произошла ошибка');
        }
    }

    /**
     * заказчик отклоняет отклик.
     *
     * @param Responds $respond
     *
     * @return void
     */
    public function refuseRespond(Responds $respond): void
    {
        if ($this->getRole() !== self::CUSTOMER || $respond->status !== self::STATUS_NEW) {
            throw new ForbiddenHttpException('Извините, действие недоступно');
        }
        $respond->updateAttributes(['status' => self::STATUS_CANCEL]);
    }

    /**
     *  исполнитель отказывается.
     *
     *  @return void
     */
    public function refuse(): void
    {
        if ($this->getRole() !== self::EXECUTOR || $this->model->status !== self::STATUS_PROGRESS) {
            throw new ForbiddenHttpException('Извините, действие недоступно');
        }
        $this->fail();
    }

    /**
     * поменять статус задания на проваленное.
     *
     * @throws ServerErrorHttpException
     */
    private function fail(): void
    {
        $this->model->status = self::STATUS_FAIL;
        if (!$this->model->save()) {
            throw new ServerErrorHttpException('при сохранении произошла ошибка');
        }
    }

    /**
     * заказчик удаляет задание.
     *
     * @return void
     */
    public function cancelTask(): void
    {
        if ($this->getRole() !== self::CUSTOMER || $this->model->status !== self::STATUS_NEW) {
            throw new ForbiddenHttpException('Извините, действие недоступно');
        }
        $this->model->updateAttributes(['status' => self::STATUS_CANCEL]);
    }

    /**
     * заказчик завершает задание.
     *
     * @param RespondForm $data данные отзыва
     *
     * @throws ServerErrorHttpException
     *
     * @return void
     */
    public function complete(RespondForm $data): void
    {
        if ($this->getRole() !== self::CUSTOMER || $this->model->status !== self::STATUS_PROGRESS) {
            throw new ForbiddenHttpException('Извините, действие недоступно');
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $review = new Reviews();
            $review->task_id = $this->model->id;
            $review->user_id = $this->model->executor_id;
            $review->value = $data->mark;
            $review->comment = $data->comment;
            if (!$review->save()) {
                throw new \Exception('Не удалось сохранить');
            }
            // проверка успешности
            if ($data->answer) {
                $this->model->updateAttributes(['status' => self::STATUS_COMPLETED]);    
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
     * @param RespondForm $data данные отклика
     */
    public function respond(RespondForm $data): void
    {
        if (!$this->isUserAllowedToRespond()) {
            throw new ForbiddenHttpException('Извините, действие недоступно'); 
        }
        $respond = new Responds();
        $respond->task_id = $this->model->id;
        $respond->author_id = $this->user_id;
        $respond->price = $data->price;
        $respond->comment = $data->comment;
        if (!$respond->save()) {
            throw new ServerErrorHttpException('при сохранении произошла ошибка');
        }
    }
}
