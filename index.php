<?php

session_start();

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");
require_once(__DIR__ . "/Todo.php");

$todoApp = new \MyApp\Todo();
$todos = $todoApp->getAll();

// echo('<pre>');
// var_dump($todos);
// echo('</pre>');
// exit;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="todo.js"></script>
	<title>My To Do List</title>
</head>
<body>
	<div class="container">
		<h1>Todos</h1>
		<form action="" id="new_todo_form">
			<input type="text" id="new-todo" placeholder="what needs to be done?">
		</form>
		<ul id="todos">
		<?php foreach ($todos as $todo) : ?>
			<li id="todo_<?php echo h($todo->id); ?>" data-id="<?php echo h($todo->id); ?>">
				<input class="update_todo" type="checkbox" <?php if ($todo->state === "1") { echo "checked"; }
				?>>
				<span class="todo_title <?php if ($todo->state === "1") { echo "done"; } ?>">
					<?php echo h($todo->title); ?>
				</span>
				<div class="delete-todo">x</div>
			</li>
		<?php endforeach; ?>
        <li id="todo-temple" data-id="" style="display: none;">
            <input class="update_todo" type="checkbox">
            <span class="todo_title"></span>
            <div class="delete-todo">x</div>
        </li>

		</ul>
	</div>
    <input type="hidden" id="token" value="<?php echo h($_SESSION['token']); ?>">
</body>
</html>