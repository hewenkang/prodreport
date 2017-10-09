<?php 
// 获取工序信息，用于addOrdInfo和显示“工序”复选框
$sql = "SELECT * FROM prosteps";
$msg = "获取产品工序时遇到错误";
$arr_step = getRows(doQuery($pdo, $sql));
// 删除订单
if (isset($_GET['del'])) {
  $sql = 'DELETE FROM orders WHERE ordid='.$_GET['del'];
  doExecute($pdo, $sql);
}

if (isset($_POST['addOrdInfo'])) {
  $sql = 'INSERT INTO orders VALUE(null,:ordno,:ordtype,:prodname,:stepids,:stepnames,:insertdate)';
  $value["ordno"]=$_POST["ordno"];
  $value["ordtype"]=$_POST["ordtype"];
  $value["prodname"]=$_POST["prodname"];
  $value["stepids"]=implode(",",$_POST["stepid"]);
  // 根据工序选择生成对应的工序名数组
  if (count($_POST["stepid"])!=0) {
    $stepname = array();
    foreach ($_POST["stepid"] as $v) {
      $stepname[]=$arr_step[$v-1]["stepname"];
    }
  }
  else {
    exit_error("工序数据不能为空");  
  }
  $value["stepnames"]=implode(",",$stepname);
  $value["insertdate"]=date('y-m-d');
  $result = doBindExecute($pdo, $sql, $value);
}

$sql = "SELECT * FROM orders";
$msg = "获取订单信息时遇到错误";
$arr_order = getRows(doQuery($pdo, $sql));
include 'v_ordInfo.html.php';
exit();
 ?>