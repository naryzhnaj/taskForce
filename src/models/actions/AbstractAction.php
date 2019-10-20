<?php

namespace TaskForse\Models;

abstract class AbstractAction
{
    abstract public static function getName();

    abstract public static function getValue();

    abstract public static function checkAccess($user_id, $executor_id);
}
