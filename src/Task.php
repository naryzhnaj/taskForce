<?php

namespace TaskForse;

class Task
{
    public static $STATUS_NEW = 'new';
    public static $STATUS_PROGRESS = 'in_progress';
    public static $STATUS_CANCEL = 'cancel';
    public static $STATUS_COMPLETE = 'complete';

    public static $CUSTOMER = 'customer';
    public static $EXECUTOR = 'executor';

    public static $ACTION_REFUZE = 'refuse';
    public static $ACTION_RESPOND = 'respond';
    public static $ACTION_WRITE = 'write';

    private $customer = null;
    private $executor = null;
    private $status = null;
    private $deadline = null;

    public function __construct($executor, $deadline)
    {
        if ($executor && $deadline && strtotime($deadline)) {
            $this->executor = $executor;
            $this->deadline = strtotime($deadline);
        } else {
            throw new Exception('ошибка в параметрах');
        }
        $this->status = self::$STATUS_NEW;
    }

    public function getOperationList($role)
    {
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusNext($role, $action)
    {
        return $this->status;
    }
}
