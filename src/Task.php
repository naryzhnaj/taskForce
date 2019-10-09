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

    public static $ACTION_REFUSE = 'refuse';
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

    private function getOperations($role)
    {
        switch ($this->status) {
            case self::$STATUS_NEW:
                switch ($role) {
                    case self::$CUSTOMER:
                        return [self::$ACTION_WRITE => self::$STATUS_NEW,
                                self::$ACTION_RESPOND => self::$STATUS_PROGRESS, ];

                    case self::$EXECUTOR:
                        return [self::$ACTION_REFUSE => self::$STATUS_CANCEL];
                } break;

                case self::$STATUS_PROGRESS:
                    return ($role === self::$CUSTOMER) ? [self::$ACTION_REFUSE => self::$STATUS_NEW] : [];

            case self::$STATUS_CANCEL: return [];

            case self::$STATUS_COMPLETE: return [];
        }
    }

    public function getActionList($role)
    {
        return array_keys($this->getOperations($role)) ?? [];
    }

    public function getStatusNext($role, $act)
    {
        $actions = $this->getOperations($role);
        if (isset($actions) && $act && isset($actions[$act])) {
            $this->status = $actions[$act];
        }

        return $this->status;
    }
}
