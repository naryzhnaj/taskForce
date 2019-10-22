<?php

use TaskForse\Models\Task;
use TaskForse\Models\Admit;
use TaskForse\Models\Complete;
use TaskForse\Models\Respond;
use TaskForse\Models\Refuse;
use TaskForse\Models\Write;

require_once 'vendor/autoload.php';

$id_customer = 0;
$id_executor = 1;
$task = new Task('test task', $id_customer);

// новый заказ
assert(implode('', $task->getActionList($id_executor)) == Respond::class);
assert(implode('', $task->getActionList($id_customer)) == Admit::class . Refuse::class);
assert($task->getStatusNext($id_executor, Respond::class) == Task::STATUS_NEW, 'start test');

// отклик принят
assert($task->getStatusNext($id_customer, Admit::class) == Task::STATUS_PROGRESS, 'progress test');
assert($task->getStatusNext($id_executor, Write::class) == Task::STATUS_PROGRESS, 'write test');
assert(implode('', $task->getActionList($id_executor)) == Refuse::class . Write::class);
assert(implode('', $task->getActionList($id_customer)) == Complete::class . Write::class);

// исполнитель взялся, заказчик не вправе отменять
assert($task->getStatusNext($id_customer, Refuse::class) == Task::STATUS_PROGRESS, 'cancel test');

// задание выполнено
assert($task->getStatusNext($id_customer, Complete::class) == Task::STATUS_COMPLETED, 'complete test');

// заказчик отменил пустую задачу
$task2 = new Task('test task', $id_customer);
assert($task2->getStatusNext($id_customer, Refuse::class) == Task::STATUS_CANCEL, 'cancel test');
