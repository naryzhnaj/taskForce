<?php

namespace TaskForse;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_PROGRESS = 'in_progress';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_COMPLETE = 'complete';
    public const STATUS_FAIL = 'fail';

    public const CUSTOMER = 'customer';
    public const EXECUTOR = 'executor';

    public const ACTION_ADMIT = 'admit';
    public const ACTION_REFUSE = 'refuse';
    public const ACTION_RESPOND = 'respond';
    public const ACTION_WRITE = 'write';

    private $title = null;
    private $customer_id = null;
    private $city_id = null;
    private $category_id = null;
    private $location = null;
    private $deadline = null;
    private $budget = null;
    private $details = null;
    private $file = null;

    private $estimate = null;
    private $status = null;
    private $executor_id = null;

    public function __construct($data, $customer_id)
    {
        $this->title = $data;
        $this->customer_id = $customer_id;
        $this->status = self::STATUS_NEW;
    }

    private function getRole($id)
    {
        return ($id === $this->customer_id) ? self::CUSTOMER : self::EXECUTOR;
    }

    private function getOperations($id)
    {
        $role = $this->getRole($id);
        $res = [];

        switch ($this->status) {
            case self::STATUS_PROGRESS:
                if ($role === self::EXECUTOR) {
                    $res = [self::ACTION_REFUSE => self::STATUS_FAIL];
                } break;

            case self::STATUS_NEW:
                switch ($role) {
                    case self::EXECUTOR:
                        $res = [self::ACTION_WRITE => null,
                                self::ACTION_RESPOND => null, ]; break;

                    case self::CUSTOMER:
                        $res = [self::ACTION_WRITE => null,
                                self::ACTION_ADMIT => self::STATUS_PROGRESS,
                                self::ACTION_REFUSE => self::STATUS_CANCEL, ];
                }
        }

        return $res;
    }

    public function getActionList($id)
    {
        return array_keys($this->getOperations($id)) ?? [];
    }

    public function getStatusNext($id, $act)
    {
        if (!$act) {
            throw new Exception('Нужно указать действие');
        }
        $actions = $this->getOperations($id);
        if (isset($actions) && isset($actions[$act])) {
            $this->status = $actions[$act];
        }

        return $this->status;
    }
}
