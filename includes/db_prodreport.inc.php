<?php 
try {
	$pdo = new PDO('mysql:host=localhost;dbname=prodreport','jxpr178','pr801jx');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
} catch (PDOException $e) {
	$errmsg = '连接生产月报数据库时出错';
	include '../error.html.php';
	exit();
}
 ?>