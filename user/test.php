<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/includes/var.inc.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/includes/db_function.inc.php";
include_once "../includes/function_quota.inc.php";
include_once "../includes/db_quota.inc.php";

session_start();
if (!isset($_SESSION["path"])||$_SESSION["path"]!==$quotaPath) {
    header("Location:".$mp);
}

// 退出登录
if (isset($_GET['Logout'])) {
  session_destroy();
  header("Location:".$mp);
}

// 进入“修改密码”页面
if(isset($_GET['ChaPwd'])){
  //检测是否具有“修改密码”的权限
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
// 实际“修改密码”
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
}

// 处理上传的EXCEL数据 (重要)
if(isset($_GET['traquoCreate'])) {
  // 检测是否具备权限
  if ( !in_array("traquoCre", $_SESSION['authority']) ) {
    $errmsg = '<p>您没有访问此页面的权限，请联系管理员</p>';
    include '../../error.html.php';
    exit();
  }
 
  //用于在显示上次选中的下拉列表项
  $comid = $_POST['company'];
  $procode2 = $_POST['product'];  
  $outtype = $_POST['outtype'];

  //获取其他必要信息
  $sql="SELECT proid, procode FROM products WHERE procode2='".$_POST['product']."'";
  $msg = '获取产品信息时遇到错误';
  $info_prod=getRows(doQuery($pdo,$sql,$msg));

  $sql="SELECT outtname, outtcode FROM outtype WHERE outtid='".$_POST['outtype']."'";
  $msg = '获取部件信息时遇到错误';
  $info_outt=getRows(doQuery($pdo,$sql,$msg));

  // 导入excel数据
  require_once '../../includes/PHPExcel.php';
  try {
    $inputFileType = PHPExcel_IOFactory::identify($_FILES["excel"]["tmp_name"]);
    if($inputFileType === "Excel2007" or $inputFileType === "Excel5"){
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($_FILES["excel"]["tmp_name"]);
    }
    else {
      echo "请上传Excel 2003或Excel 2007文件<br>";
    }
  } catch (PHPExcel_Reader_Exception $e) {
    echo "读取文件失败，请检查文件<br>";
    exit();
  }
  // 处理导入和excel数据
  $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
  $objPHPExcel->disconnectWorksheets();
  unset($objPHPExcel);
  $count = count($sheetData); // excel总行数
  try {
    $comname = ""; // 记录部件名
    $comcode = ""; // 记录部件编号
    $errpage = array(); // 记录出现错误的excel行号

    for($i=2; $i<$count; $i++) { // 第一行是表头，跳过
      $arr = $sheetData[$i];
      // 判断为空行，则跳过
      if ($arr['A']===NULL) { 
        continue;
      }
      // 判断为“部件名”行，获取“部件名”后跳过
      if ($arr['A']==0) { 
        $comname = $arr['B']; 
        $sql="SELECT comcode FROM components WHERE comname='".$comname."'";
        $msg = '获取部件信息时遇到错误';
        $info_comp=getRows(doQuery($pdo,$sql,$msg));     
      }
      else // 处理定额数据，插入quotas表
      {
        if ($arr['L']===NULL) {
          // 处理没有“单件重量”的情况（实际为一行数据粘贴到了两行上）
           $errpage[] = $i;
           continue;
        }
        if ($sheetData[$i+1]['H']!==NULL) {
          // 处理的“牌号”部分信息到了下一行的情况
           $errpage[] = $i;
           continue;
        }
        $temp = '00'.(string)$arr['A'];
        // 构建“零件编码”数据项（最重要）
        $str = $_POST['product'].'-'.$info_comp[0]['comcode'].'-'.
          $info_outt[0]['outtcode'].'-'.substr($temp,strlen($temp)-3).' '.
          $info_prod[0]['procode'].'-'.$comname.'-'.
          $info_outt[0]["outtname"].'-'.$arr['C'];
        // 定额信息插入quotas表
        $sql = "INSERT INTO quotas SET". 
          " proid='".$info_prod[0]['proid']."',".
          " date='".date("Y-m-d")."',".
          " comid='".$_POST['company']."',".
          " outtype='".$info_outt[0]["outtname"]."',".
          " objname='".$arr['C']."',".
          " comname='".$comname."',".
          " comcode='".$str."',".
          " number='".$arr['D']."',".
          " shape='".$arr['G']."',".
          " markcode='".$arr['H']."',".
          " typecode='".$arr['I']."',".
          " cutsize='".$arr['K']."',".
          " oneheavy='".$arr['L']."',".
          " totalhvy='".$arr['M']."';";
        $pdo->exec($sql);
      }
    }
  } catch (PDOException $e) {
    echo $sql."<br>".$e->getMessage();
  }     
}
// 结束处理上传的EXCEL数据 (重要)

// 处理traquoCreate模块的导出excel操作，直接导出quotas表的所有记录，然后将记录移入allquotas表
if (isset($_GET['export'])) {
  // 检测是否具备权限
  if ( !in_array("traquoCre", $_SESSION['authority']) ) {
    $errmsg = '<p>您没有访问此页面的权限，请联系管理员</p>';
    include '../../error.html.php';
    exit();
  }
  // 准备需要导出的数据
  $sql="SELECT * FROM quotas";
  $msg = '获取定额信息时遇到错误';
  $arr_quotas=getRows(doQuery($pdo,$sql,$msg));
  // 开始导出为excel文件
  require_once '../../includes/PHPExcel.php';

  $objPHPExcel = new PHPExcel();
  // 设置列自动宽
  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
  
  // 写入表头
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', '出库类别')
              ->setCellValue('B1', '物品名称')
              ->setCellValue('C1', '部件名称')
              ->setCellValue('D1', '零件编码')
              ->setCellValue('E1', '数/辆')
              ->setCellValue('F1', '名称')
              ->setCellValue('G1', '牌号及标准代号')
              ->setCellValue('H1', '品种及标准代号')
              ->setCellValue('I1', '下料尺寸')
              ->setCellValue('J1', '单件')
              ->setCellValue('K1', '合计');

  // 写入具体数据
  // $count = 2;
  // foreach ($arr_quotas as $value) {
  //   $objPHPExcel->setActiveSheetIndex(0)
  //               ->setCellValue('A'.$count, $value['outtype'])
  //               ->setCellValue('B'.$count, $value['objname'])
  //               ->setCellValue('C'.$count, $value['comname'])
  //               ->setCellValue('D'.$count, $value['comcode'])
  //               ->setCellValue('E'.$count, $value['number'])
  //               ->setCellValue('F'.$count, $value['shape'])
  //               ->setCellValue('G'.$count, $value['markcode'])
  //               ->setCellValue('H'.$count, $value['typecode'])
  //               ->setCellValue('I'.$count, $value['cutsize'])
  //               ->setCellValue('J'.$count, $value['oneheavy'])
  //               ->setCellValue('K'.$count, $value['totalhvy']);
  //   $count += 1;
  //}
  // Redirect output to a client’s web browser (Excel2007)
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="quotas.xlsx"');
  header('Cache-Control: max-age=0');
  // If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');
  // If you're serving to IE over SSL, then the following may be needed
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
  header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header ('Pragma: public'); // HTTP/1.0
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');

  $objPHPExcel->disconnectWorksheets();
  unset($objPHPExcel);
  
  // 导出excel后，将quotas中的数据导入allquotas表进行统一管理
  try {
    $pdo->beginTransaction();
    $pdo->exec("insert into allquotas select * from quotas");
    $pdo->exec("delete from quotas");
    $pdo->commit();
  } catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
  }
}


// 默认显示“定额生成”页
// 取得所有“公司”的信息，用于生成下拉列表
$sql="SELECT comid, comname FROM companies";
$msg = '获取公司信息时遇到错误';
$arr_com=getRows(doQuery($pdo,$sql,$msg));
// 取得所有“产品型号”的信息，用于生成下拉列表
$sql="SELECT procode, proname, procode2 FROM products";
$msg = '获取产品信息时遇到错误';
$arr_prod=getRows(doQuery($pdo,$sql,$msg));
// 取得所有“出库类别”的信息，用于生成下拉列表
$sql="SELECT * FROM outtype";
$msg = '获取部件信息时遇到错误';
$arr_outtype=getRows(doQuery($pdo,$sql,$msg));
// 获取新插入定额数据，用于显示表格
if (isset($_POST['company'])) {
  // 此时$_POST['product']和$_POST['outtype']必定也已同时存在
  $sql="SELECT * FROM quotas WHERE comid='".$_POST['company']."' and proid='".
  $info_prod[0]['proid']."' and outtype='".$info_outt[0]["outtname"]."'";
  $msg = '获取定额信息时遇到错误';
  $arr_quotas=getRows(doQuery($pdo,$sql,$msg));
}


  include "traQuoCre.html.php";
?>