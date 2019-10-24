<?php

declare(strict_types=1);

namespace TaskForse\Models;

use TaskForse\Ex\FileFormatException;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_PROGRESS = 'in_progress';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAIL = 'fail';
    private const AVALIABLE_STATUSES = [self::STATUS_NEW, self::STATUS_PROGRESS, self::STATUS_CANCEL, self::STATUS_COMPLETED, self::STATUS_FAIL];
    private const AVALIABLE_ACTIONS = [Admit::class, Complete::class, Refuse::class, Respond::class, Write::class];

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
     * Task constructor.
     *
     * @param string $data        название задачи
     * @param int    $customer_id id заказчика
     *
     * @throws FileFormatException
     */
    public function __construct(string $data, int $customer_id, string $status = self::STATUS_NEW)
    {
        if (!in_array($status, self::AVALIABLE_STATUSES)) {
            throw new FileFormatException('incorrect status');
        } elseif (!$data) {
            throw new FileFormatException('incorrect title');
        }
        $this->title = $data;
        $this->customer_id = $customer_id;
        $this->status = $status;
    }

    /**
     * определение роли активного пользователя по id.
     *
     * @param int $id id пользователя
     *
     * @return string $role роль
     */
    private function getRole(int $id): string
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
     * определение карты переходов статуса задачи в зависимости от роли, текущего статуса и действий.
     *
     * @param int $id id активного пользователя
     *
     * @return array $res доступные действия и соответсвующие им переходы состояния
     */
    private function getOperations(int $id): array
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
     * определение списка доступных пользователю действий.
     *
     * @param int $id id активного пользователя
     *
     * @return array
     */
    public function getActionList(int $id): array
    {
        return array_keys($this->getOperations($id)) ?? [];
    }

    /**
     * определение следующего статуса задачи.
     *
     * @param int    $id  id пользователя
     * @param string $act класс-действие
     *
     * @throws FileFormatException
     *
     * @return string статус
     */
    public function getStatusNext(int $id, string $act): string
    {
        if (!in_array($act, self::AVALIABLE_ACTIONS)) {
            throw new FileFormatException('incorrect action');
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
