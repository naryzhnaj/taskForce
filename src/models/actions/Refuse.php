<?php

namespace TaskForse\Models;

class Refuse extends AbstractAction
{
    public static function getName()
    {
        return 'refuse';
    }

    public static function getValue()
    {
        return 'Отказаться';
    }

    public static function checkAccess($user_id, $executor_id)
    {
        return $user_id === $executor_id;
    }
}
