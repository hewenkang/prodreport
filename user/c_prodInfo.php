<?php 
if (isset($_POST['addProdInfo'])) {
  $sql = 'INSERT INTO prodinfo VALUE(null,:prodcode,:prodname,:prodmark)';
  $value["prodcode"]=$_POST["prodcode"];
  $value["prodname"]=$_POST["prodname"];
  $value["prodmark"]=$_POST["prodmark"];
  $result = doBindExecute($pdo, $sql, $value);
}
// 删除产品
if (isset($_GET['del'])) {
  $sql = 'DELETE FROM prodinfo WHERE prodid='.$_GET['del'];
  doExecute($pdo, $sql);
}
$sql = "SELECT * FROM prodinfo";
$arr_prod = getRows(doQuery($pdo, $sql));
include 'v_prodInfo.html.php';
exit();
 ?>