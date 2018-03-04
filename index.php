<?php
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ДЗ к занятию 4.1 PHP</title>
    <style>
        table {
            margin-top: 20px;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid black;
            padding: 5px 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<form method="get" action="/">
    <input placeholder="Название" name="name" value="<?= $_GET? $_GET['name'] : "" ?>">
    <input placeholder="Автор" name="author" value="<?= $_GET? $_GET['author']: "" ?>">
    <input placeholder="ISBN" name="isbn" value="<?= $_GET? $_GET['isbn']: "" ?>">
    <input type="submit">
</form>
<table>
<?php

$servername = "localhost";
$username = "mslyunkov";
$password = "neto1520";

$pdo = new PDO("mysql:host=$servername;dbname=global;charset=UTF8", $username, $password);
if (!$pdo)
{
    die('Could not connect');
}



$sql = ["SELECT * FROM books"];
$values = [];

$allowedKeys = ['name', 'author', 'isbn'];

foreach ($_GET as $key=>$query) {
    if (array_search($key, $allowedKeys) !== false) {
        if ($query) {
            if (count($sql) === 1) {
                $sql[] = "WHERE $key like ?";
            } elseif (count($sql) > 1) {
                $sql[] = "AND $key like ?";
            }
            $values[] = "%$query%";
        }
    }
}

$sql = implode(' ', $sql);
$stmt = $pdo->prepare($sql);
$stmt->execute($values);
$list = $stmt->fetchAll();
?>
<tr>
    <th>Id</th>
    <th>Название</th>
    <th>Автор</th>
    <th>Год</th>
    <th>ISBN</th>
    <th>Жанр</th>
</tr>
<?php
foreach ($list as $row) {

    echo '<tr>';
    foreach ($row as $key => $name) {
        if (!is_int($key)) {
            echo "<td>$name</td>";
        }
    }
}
echo '</tr>';
?>
</table>
</body>
</html>
