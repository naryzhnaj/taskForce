<?php

namespace TaskForse\Models;

class Write extends AbstractAction
{
    public static function getName()
    {
        return 'write';
    }

    public static function getValue()
    {
        return 'Написать сообщение';
    }

    public static function checkAccess($user_id, $executor_id)
    {
        return $user_id === $executor_id;
    }
}
