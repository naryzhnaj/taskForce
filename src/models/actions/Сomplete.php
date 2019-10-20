<?php

namespace TaskForse\Models;

class Сomplete extends AbstractAction
{
    public static function getName()
    {
        return 'complete';
    }

    public static function getValue()
    {
        return 'Завершить';
    }

    public static function checkAccess($user_id, $executor_id)
    {
        return $user_id === $executor_id;
    }
}
