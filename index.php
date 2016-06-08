<?php

include("settings.php");

$pdo = new PDO($db_config["type"] . ":host=" . $db_config["host"] . ";dbname=" . $db_config["dbname"], $db_config["name"], $db_config["password"]);

$pdo->exec("SET NAMES utf8;");

// Сам запрос
$sql = "SELECT * FROM `books`";
// Добавочный запрос для фильтра
$sql_add = "";
// Список параметров для запроса
$params = array();

// Проверяем передается ли параметр ISBN в GET
if(isset($_GET["isbn"]) && !empty($_GET["isbn"])) {
	if(empty($sql_add)) {
		$sql_add = " WHERE `isbn` = ?";
	} else {
		$sql_add .= " AND `isbn` = ?";
	}
	$params[] = $_GET["isbn"];
}

// Проверяем передается ли параметр NAME в GET
if(isset($_GET["name"]) && !empty($_GET["name"])) {
	if(empty($sql_add)) {
		$sql_add = " WHERE `name` = ?";
	} else {
		$sql_add .= " AND `name` = ?";
	}
	$params[] = $_GET["name"];
}

// Проверяем передается ли параметр AUTHOR в GET
if(isset($_GET["author"]) && !empty($_GET["author"])) {
	if(empty($sql_add)) {
		$sql_add = " WHERE `author` = ?";
	} else {
		$sql_add .= " AND `author` = ?";
	}
	$params[] = $_GET["author"];
}

// Объединяем запросы
$sql .= $sql_add;

$stm = $pdo->prepare($sql);
$stm->execute($params);

$res = $stm->fetchAll();

?>

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
</style>
<h1>Библиотека успешного человека</h1>

<form method="GET">
    <input type="text" name="isbn" placeholder="ISBN" value="" />
    <input type="text" name="name" placeholder="Название книги" value="" />
    <input type="text" name="author" placeholder="Автор книги" value="" />
    <input type="submit" value="Поиск" />
</form>

<?php if(count($res) > 0) { ?>
<table>
    <tr>
        <th>Название</th>
        <th>Автор</th>
        <th>Год выпуска</th>
        <th>Жанр</th>
        <th>ISBN</th>
    </tr>
    <?php foreach($res AS $item) { ?>
	<tr>
	  <td><?php echo $item["name"]; ?></td>
	  <td><?php echo $item["author"]; ?></td>
	  <td><?php echo $item["year"]; ?></td>
	  <td><?php echo $item["genre"]; ?></td>
	  <td><?php echo $item["isbn"]; ?></td>
	</tr>
	<?php } ?>
</table>

<?php } ?>