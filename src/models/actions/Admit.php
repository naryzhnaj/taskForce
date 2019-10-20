<?php

namespace TaskForse\Models;

class Admit extends AbstractAction
{
    public static function getName()
    {
        return 'admit';
    }

    public static function getValue()
    {
        return 'Принять';
    }

    public static function checkAccess($user_id, $executor_id)
    {
        return $user_id === $executor_id;
    }
}
