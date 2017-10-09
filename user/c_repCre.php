<?php 
// 用于显示月报年月和创建月报
$year = date('Y');
$month = date('m');
// 添加订单
// 先查看订单和产品是否已经存在（记录是否不为零）
if (isset($_POST['addOrder'])) {
  $sql = "SELECT count(*) FROM curorders WHERE ordno='".$_POST['ordno'].
      "' AND prodname='".$_POST['prodname2']."'";
  $num = getNumber($pdo, $sql);
  if ($num == 0) {
    // 如果记录为零，则向表中插入此订单和产品的记录
    $sql = 'SELECT count(*) FROM curorders';
    $num = getNumber($pdo, $sql);
    $sql = "INSERT INTO curorders SELECT ".($num+1).", '".date("Y.m").
    "', ordno, ordtype, prodname, stepids, stepnames FROM orders WHERE ordno=:ordno AND prodname=:prodname";
    $arr['ordno'] = $_POST['ordno'];
    $arr['prodname'] = $_POST['prodname2'];
    doBindExecute($pdo, $sql, $arr);
  }
  else {
    // 如果记录不为零，则不插入数据，而是设置错误提示信息
    $err_msg = $_POST['ordno']."订单的".$_POST['prodname2']."已存在，不能重复添加";
  }
} // 添加订单操作结束

// 删除订单
// 根据记录的ID号删除，很简单
if (isset($_GET['del'])) {
  // 检查该记录是否还没生成月报，即flag是否为0
  // 其实只显示flag为0的记录，一般不会出现删除flag为1的记录的情况，但以防万一
  $sql = 'SELECT count(*) FROM curorders WHERE flag=0 AND id='.$_GET['del'];
  $num = getNumber($pdo, $sql);
  if ($num == 1) {
    // 说明些记录还没生成月报，可以删除
    $sql = 'DELETE FROM curorders WHERE flag=0 AND id='.$_GET['del'];
    doExecute($pdo, $sql);
    // 同时将此记录之后的所有记录id字段减1（如果此记录未生成月报，则之后的记录也没生成月报）
    $sql = 'UPDATE curorders SET id=id-1 WHERE id>'.$_GET['del'];
    doExecute($pdo, $sql);
  }
  else {
    $err_msg = "此记录不存在或已生成月报，不能删除";
  }
}

// 生成月报
if (isset($_POST['creReport'])) {
  // 1. 首先向“月报总计表”中插入一条记录
  // 如果当月记录已存在则不插入
  // 
  // 1.1 查看本月的记录是否存在（每个月在总计表中有一条记录）
  $sql = "SELECT count(*) FROM totalreport WHERE date='".($year.".".$month)."'";
  $num = getNumber($pdo, $sql);
  if ($num == 0) {
    // 如果不存在，则需要先获取上期结存，做为本期期初
    // 获取上个月的时间
    if ($month == 1) {
      $time = ($year - 1).".12";
    }
    else {
      $time = $year.".".substr("0".($month-1), -2);
    }
    // 获取上期结存
    $sql = "SELECT endnum FROM totalreport WHERE date='".$time."'";
    $endnum = getRows(doQuery($pdo, $sql));
    if(count($endnum)==0) {
      $lastendnum = 0;
    }
    else {
      $lastendnum = $endnum[0]['endnum'];
    }
    // 1.2 向月报总计表中插入记录
    $sql = "INSERT INTO totalreport VALUE('".($year.".".$month)."',".$lastendnum.",0,0,0,0,0,0,0,0,0,0,0,0,".$lastendnum.",0)";
    doExecute($pdo, $sql);
  } // 向“月报总计表”中插入数据操作结束
  
  
  // 2. 向当月月报表中插入多条记录
  // 选获取curorders表中还没生成过月报的记录集合
  $sql = 'SELECT a.id,a.ordno,a.ordtype,a.prodname,a.stepids,a.stepnames,b.prodcode,b.prodmark 
    FROM curorders AS a
    INNER JOIN prodinfo AS b ON a.prodname=b.prodname 
    WHERE a.flag = 0
    ORDER BY a.id';
  $arr_orders = getRows(doQuery($pdo, $sql));
  foreach ($arr_orders as $v_order) {
    // 集合中的每条记录需要选在curreport表中插入一条小计记录
    // $id2用于工序2的记录，每次从0开始
    $id2 = 0;
    $sql = "INSERT INTO curreport SET id=".$v_order['id'].
          ", id2=".$id2.
          ", date='".$year."\.".$month.
          "', prodcode='".$v_order['prodcode'].
          "', prodmark='".$v_order['prodmark'].
          "', prodname='".$v_order['prodname'].
          "', ordno='".$v_order['ordno'].
          "', ordtype='".$v_order['ordtype'].
          "', stepid='0', stepsiteid='0', stepname='小计'";
        doExecute($pdo,$sql);
    // 然后按记录中的工序信息在curreport表中插入多条工序记录
    $id2 = 1;
    // 先把记录的工序字段打散
    $arr_steps = explode(",", $v_order['stepids']);
    $num = count($arr_steps);
    // 然后查找每个工序对应的子工序（可能一对多），依次向curreport表中插入对应的工序记录
    for($i=0; $i<$num; ++$i) {
      $sql = "SELECT * FROM stepsites WHERE stepid='".$arr_steps[$i]."' ORDER BY stepsiteid";
      $arr_stepsites = getRows(doQuery($pdo, $sql));
      foreach ($arr_stepsites as $v_stepsite) {
        $sql = "INSERT INTO curreport SET id=".$v_order['id'].
          ", id2=".$id2.
          ", date='".$year."\.".$month.
          "', prodcode='".$v_order['prodcode'].
          "', prodmark='".$v_order['prodmark'].
          "', prodname='".$v_order['prodname'].
          "', ordno='".$v_order['ordno'].
          "', ordtype='".$v_order['ordtype'].
          "', stepid='".$arr_steps[$i].
          "',stepsiteid='".$v_stepsite['stepsiteid'].
          "', stepname='".$v_stepsite['stepsite']."'";
        doExecute($pdo,$sql);
        $id2 += 1;
      } // 一道工序对应的所有子工序向curreport表中插入工序记录操作结束
    } // 所有工序的子工序向curreport表中插入工序记录操作结束 
    // 把生成完月报的curorders表记录的flag字段置1
    $sql = "UPDATE curorders SET flag=1 WHERE id=".$v_order['id'];
    doExecute($pdo,$sql);
  } // curorders表中一条记录生成月报操作完成，向curreport表中插入了小计记录和工序记录 
} //  curorders表中所有记录生成月报操作完成

// 处理AJAX请求
// 处理根据模糊产品名称生成订单号的AJAX请求
if (isset($_GET['ajax'])&&isset($_GET['prodname'])) {
  $sql="SELECT distinct ordno FROM orders WHERE prodname like '%".$_GET['prodname']."%'";

  $arr_comn=getRows(doQuery($pdo,$sql));
  $response = array();
  foreach ($arr_comn as $value) {
    $response[] = $value['ordno'];
  }
  echo implode('|',$response);
  exit();
}
// 处理根据订单号生成产品名称的AJAX请求
if (isset($_GET['ajax'])&&isset($_GET['ordno'])) {
  $sql="SELECT prodname FROM orders WHERE ordno='".$_GET['ordno']."'";
  $arr_prodname=getRows(doQuery($pdo,$sql));
  $response = array();
  foreach ($arr_prodname as $value) {
    $response[] = $value['prodname'];
  }
  echo implode('|',$response);
  exit();
}

// 以下为v_repCre显示提供数据
$prodname = "RD2";
if (isset($_POST['prodname'])) {
  $prodname = $_POST['prodname'];
}
// $arr_selorder用于模糊查询时显示“订单”下接列表
$sql = "SELECT distinct ordno AS val FROM orders WHERE prodname like'%".$prodname."%'";
$arr_selorder = getRows(doQuery($pdo, $sql));
// $arr_selprodname用于显示某个订单对应的“产品名称”，默认为$arr_selorder中第一个订单对应的“产品名称”
$sql = "SELECT prodname AS val FROM orders WHERE prodname like'%".$prodname."%'".
    " AND ordno='".$arr_selorder[0]['val']."'";
$arr_selprodname = getRows(doQuery($pdo, $sql));
// $arr_order用于显示本月订单，flag为1的记录不显示
$sql = 'SELECT * FROM curorders WHERE flag = 0 ORDER BY id';
$arr_order = getRows(doQuery($pdo, $sql));
include 'v_repCre.html.php';
exit();
?>