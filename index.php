<?php

use TaskForse\Task;

require_once 'vendor/autoload.php';

$task = new Task('test task');
$id_customer = 0;
$id_executor = 1;
assert_options(ASSERT_BAIL, 1);

// новый заказ
echo 'person = executor, status = new, actions = '.implode(', ', $task->getActionList($id_executor)).'<br>';
echo 'person = customer, status = new, actions = '.implode(', ', $task->getActionList($id_customer)).'<br>';

// отклик принят
assert($task->getStatusNext($id_customer, Task::ACTION_ADMIT) == Task::STATUS_PROGRESS, 'progress test');
echo $task->getStatusNext($id_customer, Task::ACTION_ADMIT).'<br>';
echo 'person = customer, status = progress, actions = '.implode(', ', $task->getActionList($id_customer)).'<br>';
echo 'person = executor, status = progress, actions = '.implode(', ', $task->getActionList($id_executor)).'<br>';

// исполнитель взялся, заказчик не вправе отменять
assert($task->getStatusNext($id_customer, Task::ACTION_REFUSE) == Task::STATUS_PROGRESS, 'cancel test').'<br>';
echo $task->getStatusNext($id_customer, Task::ACTION_REFUSE).'<br>';

// исполнитель отказался
assert($task->getStatusNext($id_executor, Task::ACTION_REFUSE) == Task::STATUS_FAIL, 'fail test').'<br>';
echo $task->getStatusNext($id_executor, Task::ACTION_REFUSE).'<br>';

// заказчик отменил пустую задачу
$task2 = new Task('test task');
assert($task2->getStatusNext($id_customer, Task::ACTION_REFUSE) == Task::STATUS_CANCEL, 'cancel test');
echo $task2->getStatusNext($id_customer, Task::ACTION_REFUSE);
