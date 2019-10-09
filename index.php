<?php

use TaskForse\Task;

require_once 'vendor/autoload.php';

$task1 = new Task(1, '2019-10-10');

// исполнитель написал
echo $task1->getStatusNext(Task::$CUSTOMER, Task::$ACTION_WRITE).'<br>';

// исполнитель взялся
echo $task1->getStatusNext(Task::$CUSTOMER, Task::$ACTION_RESPOND).'<br>';

// исполнитель взялся, заказчик не вправе отменять
echo $task1->getStatusNext(Task::$EXECUTOR, Task::$ACTION_REFUSE).'<br>';

// исполнитель отказался
echo $task1->getStatusNext(Task::$CUSTOMER, Task::$ACTION_REFUSE).'<br>';

// заказчик отменил пустую задачу
echo $task1->getStatusNext(Task::$EXECUTOR, Task::$ACTION_REFUSE);
