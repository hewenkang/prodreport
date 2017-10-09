<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/includes/var.inc.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/includes/db_function.inc.php";
include_once "./includes/function_prodreport.inc.php";
include_once "./includes/db_prodreport.inc.php";

session_start();
if (!isset($_SESSION["path"])||$_SESSION["path"]!==$prodreportPath) {
  header("Location:".$mp);
  exit();
} 

$_SESSION['modules'] = getUserModules($pdo, $_SESSION['name']);

$_SESSION['all_modules'] = getAllModules($pdo);

// 创造用户权限项，用于提高安全性
foreach ($_SESSION['modules'] as $value) {
  for ($i=count($value); $i > 1; $i--) { 
    $_SESSION['authority'][] = $value[$i - 1];
  }
}

switch(getUserRoles($pdo, $_SESSION["name"])) {
  case "月报填写":
    $_SESSION["role"]="月报填写";
    header("Location:".$_SESSION["path"]."/user/");
    break;
  case "管理员":
    $_SESSION["role"]="管理员";
    header("Location:".$_SESSION["path"]."/admin/");
    break;
  default:
  header("Location:".$mp);  
}
?>