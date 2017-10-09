<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/includes/var.inc.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/includes/db_function.inc.php";
include_once "../includes/function_prodreport.inc.php";
include_once "../includes/db_prodreport.inc.php";

// 1. 全局功能
// 1.1 路径跳转
session_start();
if (!isset($_SESSION["path"])||$_SESSION["path"]!==$prodreportPath) {
  var_dump($_SESSION);
    header("Location:".$mp);
    exit();
}

// 1.2 退出登录
if (isset($_GET['Logout'])) {
  session_destroy();
  header("Location:".$mp);
}

// 1.3 进入“修改密码”页面
if(isset($_GET['ChaPwd'])){
  // 1.3.1 检测是否具有“修改密码”的权限
  if(!isset($_SESSION['modules']))
  {
    $errmsg = '<p>您没有访问此页面的权限，请联系管理员</p>';
    include '../../error.html.php';
    exit();
  }
  $flag = false;
  foreach ($_SESSION['modules'] as $key => $value) {
    if (in_array("ChaPwd", $value)) {
      $flag = true;
    }
  }
  if ($flag == false) {
    $errmsg = '<p>您没有访问此页面的权限，请联系管理员</p>';
    include '../../error.html.php';
    exit();
  }
  include 'changepwd.html.php';
  exit();
}

// 1.4 实际“修改密码”
if(isset($_GET['changepwd'])){

  if($_POST['oldpwd']!==$_SESSION['password']){
    $msg="输入的原密码有误，请重新输入";
    include 'changepwd.html.php';
    exit();
  }
  if($_POST['newpwd1']!==$_POST['newpwd2']){
    $msg="两次输入的新密码不同，请重新输入";
    include 'changepwd.html.php';
    exit();
  }
  try {
    include_once '../../includes/db_main.inc.php';
    $sql="UPDATE users SET password='".MD5($_POST['newpwd2'])."' WHERE username='".$_SESSION['name']."'";
    $pdo->exec($sql);
    session_destroy();
    header("Location:".$mp);
    exit();
  } catch (PDOException $e) {
    $errmsg = '修改密码时发生错误。<br>'.$e->getMessage().
    '<br>'.$sql;
    include $_SERVER['DOCUMENT_ROOT'].'/error.html.php';
    exit();
  } 
} // 1.4 结束实际“修改密码”

// 2. 基础数据维护
// ------------------------------------------------------------------------------
// 2.1 生产工序维护
if (isset($_GET['stepInfo'])) {
  // 2.1.1 检测是否具备权限
  if ( !in_array("stepInfo", $_SESSION['authority']) ) {
    $errmsg = '<p>您没有访问此页面的权限，请联系管理员</p>';
    include '../../error.html.php';
    exit();
  }
  if (isset($_POST['addStep'])) {
    // 2.1.3 获取必要数据
    $sql = "INSERT INTO prosteps SET stepname='".$_POST['stepname']."'";
    doExecute($pdo, $sql);
  }
  // 取得所有“工序”信息，用于显示
  $sql="SELECT * FROM prosteps";
  $msg = '获取工序信息时遇到错误';
  $arr_step=getRows(doQuery($pdo,$sql,$msg));
  include "stepInfo.html.php";
  exit();
}

// ------------------------------------------------------------------------------
// 2.2 工序地点维护
if (isset($_GET['siteInfo'])) 
{
  checkUserAuthority("siteInfo", $_SESSION['authority']);
  include 'c_siteInfo.php';
}

// ------------------------------------------------------------------------------
// 2.3 订单信息维护
if (isset($_GET['ordInfo']))
{
  checkUserAuthority("ordInfo", $_SESSION['authority']);
  include 'c_ordInfo.php';
}
if (isset($_GET['prodInfo']))
{
  checkUserAuthority("prodInfo", $_SESSION['authority']);
  include 'c_prodInfo.php';
}

/////////////////////////////////////////////////////////////////////////////
// 3. 月报维护功能

// 3.1 月报生成
if (isset($_GET['repCre']))
{
  checkUserAuthority("repCre", $_SESSION['authority']);
  include 'c_repCre.php';
  exit();
} 
// 3.2 月报填写
if (isset($_GET['repWri']))
{
  checkUserAuthority("repWri", $_SESSION['authority']);
  include 'c_repWri.php';
  exit();
}

include 'index.html';
?>