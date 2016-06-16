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

// Присоединяем запрошенные данные к строке sql
$ar_params = array("name" => isset($_GET['name']) ? $_GET['name'] : NULL, "isbn" => isset($_GET['isbn']) ? $_GET['isbn'] : NULL, "author" => isset($_GET['author']) ? $_GET['author'] : NULL);
foreach($ar_params AS $type => $data) {
    $data != NULL ? (!empty($sql_add) ? $sql_add .= " AND ".$type." LIKE ?" : $sql_add .= " WHERE ".$type." LIKE ?") : '';
    $data != NULL ? ($params[] = '%'.$data.'%') : '';
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