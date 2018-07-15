<?php

require_once 'auth_reg.php';
require_once 'core.php';
require_once 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>to-do list</title>
  <style>
    table { 
      border-spacing: 0;
      border-collapse: collapse;
    }

    table td, table th {
      border: 1px solid #ccc;
      padding: 5px;
    }
    
    table th {
      background: #eee;
    }
    
    form {
      margin: 0;
    }
  </style>
</head>
<body>
  <?php if (!isRegister()) { ?>
    <a href="register.php">Войдите на сайт</a>
  <?php  } else { ?>
    <h1>Здравствуйте, <?=$_SESSION['user']?>! Вот ваш список дел:</h1>
  
    <div style="float: left; margin-bottom: 20px;">
      <?php if (isset($_GET['edit'])) { ?>
        <form method="POST">
          <input type="text" name="description" placeholder="Описание задачи" value="">
          <input type="submit" name="save1" value="Изменить задачу"><br><br>
        </form>  
      <?php } else { ?>
        <form method="POST">
          <input type="text" name="description" placeholder="Описание задачи">
          <input type="submit" name="save" value="Добавить задачу"><br><br>
        </form>
      <?php } ?>
    </div>

    <div style="float: left; margin-left: 20px;">
      <form method="POST">
        <label for="sort">Сортировать по:</label>
        <select name="sort_by">
          <option value="date_created">Дате добавления</option>
          <option value="is_done">Статусу</option>
          <option value="description">Описанию</option>
        </select>
        <input type="submit" name="sort" value="Отсортировать">
      </form>
    </div>

    <div style="clear: both"></div>

    <table>
      <tbody>
        <tr>
          <th>Описание задачи</th>
          <th>Дата добавления</th>            
          <th>Статус</th>
          <th></th>
          <th>Ответственный</th>
          <th>Автор</th>
          <th>Закрепить задачу за пользователем</th>
        </tr>
        
        <?php foreach ($statement as $task): ?>
          <tr>
            <td><?php echo htmlspecialchars($task['description']) ?></td>
            <td><?php echo htmlspecialchars($task['date_added']) ?></td>
            <?php echo htmlspecialchars($task['is_done']) ? '<td style="color: green">Выполнено</td>' : '<td style="color: orange">В процессе</td>' ?>
            <td>
              <a href="?edit=<?=$task['id']?>">Изменить</a>
              <?php if ($task['is_done']) { 
                  echo ''; 
              } else { ?>
                <a href="?done=<?=$task['id']?>">Выполнить</a> 
              <?php } ?>
              <a href="?delete=<?=$task['id']?>">Удалить</a>
            </td>
            <td><?php if ($task['assigned'] == $_SESSION['user']) {
                echo 'Вы';
            } else
                echo htmlspecialchars($task['assigned']) ?></td>
            <td><?php echo htmlspecialchars($task['author']) ?></td>
            <td>
              <form method="POST">  
                <select name="assigned_user_id">
                  <?php foreach ($rowUsers as $user) { ?>
                    <option value="<?php echo htmlspecialchars($user['id']) ?>"><?php echo htmlspecialchars($user['login']) ?></option>   
                  <?php } ?>
                </select>  
                <input type="hidden" name="newTask" value="<?php echo $task['id'] ?>">
                <input type="submit" name="assign" value="Переложить ответственность">
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    
    <p><strong>Также, посмотрите, что от Вас требуют другие люди:</strong></p>

    <table>
      <tbody>
        <tr>
          <th>Описание задачи</th>
          <th>Дата добавления</th>  
          <th>Статус</th>
          <th></th>
          <th>Ответственный</th>
          <th>Автор</th>
        </tr>
        
        <?php foreach ($statementOthers as $other) : ?>
          <tr>         
            <td><?php echo htmlspecialchars($other['description']) ?></td>
            <td><?php echo htmlspecialchars($other['date_added']) ?></td>
            <?php echo htmlspecialchars($other['is_done']) ? '<td style="color: green">Выполнено</td>' : '<td style="color: orange">В процессе</td>' ?>
            <td>
              <a href="?edit=<?=$other['id']?>">Изменить</a>
              <?php if ($other['is_done']) { 
                  echo ''; 
              } else { ?>
                  <a href="?done=<?=$other['id']?>">Выполнить</a> 
              <?php } ?>
              <a href="?delete=<?=$other['id']?>">Удалить</a>
            </td>
            <td><?php if ($other['assigned'] == $_SESSION['user']) {
                echo 'Вы';
            } else
                echo htmlspecialchars($other['assigned']) ?></td>
            <td><?php echo htmlspecialchars($other['author']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p><a href="logout.php">Выход</a></p>
  <?php } ?>
</body>
</html>