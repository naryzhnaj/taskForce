<?php

namespace TaskForse\Models;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_PROGRESS = 'in_progress';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAIL = 'fail';

    public const CUSTOMER = 'customer';
    public const EXECUTOR = 'executor';
    public const VISITOR = 'visitor';

    private $title = null;
    private $customer_id = null;
    private $city_id = null;
    private $category_id = null;
    private $location = null;
    private $post_time = null;
    private $deadline = null;
    private $budget = null;
    private $details = null;
    private $file = null;

    private $status = null;
    private $executor_id = null;

    /**
     * Task constructor
     *
     * @param string $data название задачи
     * @param int $customer_id id заказчика
     */
    public function __construct($data, $customer_id)
    {
        $this->title = $data;
        $this->customer_id = $customer_id;
        $this->status = self::STATUS_NEW;
    }

    /**
     * определение роли активного пользователя по id
     *
     * @param int $id id пользователя
     *
     * @return string $role роль
     */
    private function getRole($id)
    {
        $role = self::VISITOR;
        if ($id === $this->customer_id) {
            $role = self::CUSTOMER;
        } elseif ($id === $this->executor_id) {
            $role = self::EXECUTOR;
        }
        return $role;
    }

    /**
     * определение карты переходов статуса задачи в зависимости от роли, текущего статуса и действий
     *
     * @param int $id id активного пользователя
     *
     * @return array $res доступные действия и соответсвующие им переходы состояния
     */
    private function getOperations($id)
    {
        $role = $this->getRole($id);
        $res = [];

        switch ($this->status) {
            case self::STATUS_PROGRESS:
                switch ($role) {
                    case self::EXECUTOR:
                        $res = [Refuse::class => self::STATUS_FAIL,
                                Write::class => null, ]; break;
                    case self::CUSTOMER:
                        $res = [Complete::class => self::STATUS_COMPLETED,
                                Write::class => null, ];
                } break;

            case self::STATUS_NEW:
                switch ($role) {
                    case self::VISITOR:
                        $res = [Respond::class => null]; break;

                    case self::CUSTOMER:
                        $res = [Admit::class => self::STATUS_PROGRESS,
                                Refuse::class => self::STATUS_CANCEL, ];
                }
        }

        return $res;
    }

    /**
     * определение списка доступных пользователю действий
     *
     * @param int $id id активного пользователя
     *
     * @return array
     */
    public function getActionList($id)
    {
        return array_keys($this->getOperations($id)) ?? [];
    }

    /**
     * определение следующего статуса задачи
     *
     * @param int $id id пользователя
     * @param string $act действие
     *
     * @return string статус
     */
    public function getStatusNext($id, $act)
    {
        if (!$act) {
            throw new Exception('Нужно указать действие');
        }
        $actions = $this->getOperations($id);

        if (isset($actions) && isset($actions[$act])) {
            $this->status = $actions[$act];
            // назначаем случайного исполнителя
            if ($act === Admit::class) {
                $this->executor_id = 1;
            }
        }

        return $this->status;
    }
}
