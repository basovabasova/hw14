<?php

require_once 'config.php';
require_once 'auth_reg.php';

if (isset($_POST['save'])) {
    $query = "INSERT INTO `task` (description, user_id, assigned_user_id, date_added) VALUES (?, ?, ?, NOW())";
    $description = (string)($_POST['description']);
    $description = trim($description);
    $user_id = $_SESSION['id'];
    $assigned_user_id = $_SESSION['id'];

    if ($description !== '') {
        $statement = $pdo->prepare($query);
        $statement->execute([$description, $user_id, $assigned_user_id]);
    }
}

if (isset($_GET['done'])) {
    $query = "UPDATE task SET is_done = 1 WHERE id = ? AND assigned_user_id = ?";
    $id = (int)($_GET['done']);
    $assigned_user_id = $_SESSION['id'];

    $statement = $pdo->prepare($query);
    $statement->execute([$id, $assigned_user_id]);
    redirect('index');
}

if (isset($_GET['delete'])) {
    $query = "DELETE FROM task WHERE id = ? AND user_id =? LIMIT 1";
    $id = (int)($_GET['delete']);
    $user_id = $_SESSION['id'];

    $statement = $pdo->prepare($query);
    $statement->execute([$id, $user_id]);
    redirect('index');
}

if (isset($_POST['save1'])) {
    $query = "UPDATE task SET description = ? WHERE id = ? AND user_id = ?";
    $description = (string)($_POST['description']);
    $description = trim($description);
    $id = (int)($_GET['edit']);
    $user_id = $_SESSION['id'];

    if ($description !== '') {
        $statement = $pdo->prepare($query);
        $statement->execute([$description, $id, $user_id]);
    }
    redirect('index');
}

if (isset($_POST['assign'])) {
    $query = "UPDATE task SET assigned_user_id = ? WHERE id = ?";
    $assigned_user_id = $_POST['assigned_user_id'];
    $id = $_POST['newTask'];

    $statement = $pdo->prepare($query);
    $statement->execute([$assigned_user_id, $id]);
}

$sql = "SELECT task.id as id, task.description as description, task.date_added as date_added, task.is_done as is_done, u1.login as author, u2.login as assigned
    FROM task
    JOIN user u1 ON task.user_id=u1.id
    JOIN user u2 ON task.assigned_user_id=u2.id
    WHERE user_id = '".$_SESSION['id']."'";
$sql1 = "$sql ORDER BY date_added";
$statement = $pdo->prepare($sql1);
$statement->execute();

if (isset($_POST['sort'])) {
    if ($_POST['sort_by'] == 'date_created') {
        $statement = $pdo->prepare($sql1);
        $statement->execute();
    }

    if ($_POST['sort_by'] == 'is_done') {
        $query = "$sql ORDER BY is_done";

        $statement = $pdo->prepare($query);
        $statement->execute();
    }

    if ($_POST['sort_by'] == 'description') {  
        $query = "$sql ORDER BY description";

        $statement = $pdo->prepare($query);
        $statement->execute();
    }
}

$users = "SELECT id, login FROM `user`";

$statementUsers = $pdo->prepare($users);
$statementUsers->execute();
while($row = $statementUsers->fetch(PDO::FETCH_ASSOC)) {
    $rowUsers[] = $row;
}

$others = "SELECT task.id as id, task.description as description, task.date_added as date_added, task.is_done as is_done, u1.login as author, u2.login as assigned
    FROM task
    JOIN user u1 ON task.user_id=u1.id
    JOIN user u2 ON task.assigned_user_id=u2.id
    WHERE assigned_user_id = ?
    AND user_id <> ?";

$assigned_user_id = $_SESSION['id']; 
$user_id = $_SESSION['id']; 
$statementOthers = $pdo->prepare($others);
$statementOthers->execute([$assigned_user_id, $user_id]);