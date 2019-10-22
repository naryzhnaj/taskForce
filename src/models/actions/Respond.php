<?php

namespace TaskForse\Models;

class Respond extends AbstractAction
{
    public static function getName()
    {
        return 'respond';
    }

    public static function getValue()
    {
        return 'Откликнуться';
    }

    public static function checkAccess($user_id, $executor_id)
    {
        return $user_id === $executor_id;
    }
}
