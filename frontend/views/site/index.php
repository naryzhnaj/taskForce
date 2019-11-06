<?php

/* @var $this yii\web\View */

use app\models\Users;
use app\models\Tasks;

$this->title = 'TaskForse';
$allUsers = Users::find()->all();
$allTasks = Tasks::find()->all();
?>

<h2>current users</h2>
<table>
<?php foreach ($allUsers as $user): ?>
    <tr>
        <td><?=$user->name ?></td>
        <td><?=$user->city->title ?></td>
    </tr>
<?php endforeach; ?>
</table>

<h2>current tasks</h2>
<table>
<tr>
    <th>task</th>
    <th>category</th>
    <th>customer</th>
    <th>price</th>
</tr>
<?php foreach ($allTasks as $task): ?>
    <tr>
        <td><?=$task->title ?></td>
        <td><?=$task->category->title ?></td>
        <td><?=$task->author->name ?></td>
        <td><?=$task->budget ?></td>
    </tr>
<?php endforeach; ?>
</table>
