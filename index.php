<?php

use TaskForse\Models\Task;

require_once 'vendor/autoload.php';
$id_customer = 0;
$id_executor = 1;
$task = new Task('test task', $id_customer);

// новый заказ
assert(implode(', ', $task->getActionList($id_executor)) == 'write, respond');
assert(implode(', ', $task->getActionList($id_customer)) == 'write, admit, refuse');
assert($task->getStatusNext($id_executor, Task::ACTION_RESPOND) == Task::STATUS_NEW, 'start test');
assert($task->getStatusNext($id_executor, Task::ACTION_WRITE) == Task::STATUS_NEW, 'start test');

// отклик принят
assert($task->getStatusNext($id_customer, Task::ACTION_ADMIT) == Task::STATUS_PROGRESS, 'progress test');
assert(implode(', ', $task->getActionList($id_executor)) == 'refuse');
assert(implode(', ', $task->getActionList($id_customer)) == 'complete');

// исполнитель взялся, заказчик не вправе отменять
assert($task->getStatusNext($id_customer, Task::ACTION_REFUSE) == Task::STATUS_PROGRESS, 'cancel test');

// задание выполнено
assert($task->getStatusNext($id_customer, Task::ACTION_COMPLETE) == Task::STATUS_COMPLETED, 'complete test');

// заказчик отменил пустую задачу
$task2 = new Task('test task', $id_customer);
assert($task2->getStatusNext($id_customer, Task::ACTION_REFUSE) == Task::STATUS_CANCEL, 'cancel test');
assert(implode(', ', $task2->getActionList($id_customer)) == '');
