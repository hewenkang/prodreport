<?php 
if (isset($_POST['addStepSite'])) {
  $stepid = $_POST['stepid'];
  $sql = "SELECT IFNULL(MAX(stepsiteid),0) AS stepsiteid FROM stepsites";
  $stepsiteid = getRows(doQuery($pdo, $sql));
  //var_dump($stepsiteid);exit();
  $sql = "INSERT INTO stepsites SET stepsiteid=".$stepsiteid[0]['stepsiteid']."+1, stepid='".$_POST['stepid'].
    "', stepsite='".$_POST['stepsite']."'";
  doExecute($pdo, $sql);
}

$sql = "SELECT * FROM prosteps";
$msg = "获取产品工序时遇到错误";
$arr_step = getRows(doQuery($pdo, $sql));
$sql = "SELECT a.stepid, b.stepname, a.stepsite FROM stepsites AS a
  INNER JOIN prosteps AS b ON 
  a.stepid = b.stepid";
$msg = "获取工序地点时遇到错误";
$arr_stepsite = getRows(doQuery($pdo, $sql));
include 'siteInfo.html.php';
exit();
 ?>