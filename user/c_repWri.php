<?php 
// 用于显示月报年月和创建月报
$year = date('Y');
$month = date('m');
// 保存数据
if (isset($_POST['savereport'])) {
  $cols=array('startnum','inputnew','inputget','inputchg','totalinput','wscrap','mscrap','totalscrap',
    'outputnext','outputlab','outputstore','outputother','totaloutput','endnum','workhour');
  // 保存totalreport数据
  $sql = "UPDATE totalreport SET ";
  $conds = array();
  foreach ($cols as $key => $value) {
    if (isset($_POST["h_".$value])) {
      $conds[]=$value."=".$_POST["h_".$value];
    }
  }
  $condition = implode(',', $conds);
  $sql = $sql . $condition . " WHERE date='".($year.".".$month)."'";
  doExecute($pdo, $sql);

  // 保存curreport数据小计行
  $sql = "UPDATE curreport SET ";
  unset($conds);
  $conds = array();
  foreach ($cols as $key => $value) {
    if (isset($_POST["h_".$value."0"])) {
      $conds[]=$value."=".($_POST["h_".$value."0"]==null?0:$_POST["h_".$value."0"]);
    }
  }
  $condition = implode(',', $conds);
  $sql = $sql . $condition . " WHERE date='".($year.".".$month).
  "' AND ordno='".$_POST["h_ordno0"].
  "' AND stepname='".$_POST["h_stepname0"]."'";
  doExecute($pdo, $sql);

  // 保存curreport数据所有工序行
  unset($cols);
  $cols=array('qualified',
    'startnum','inputnew','inputget','totalinput','wscrap','mscrap','totalscrap',
    'outputnext','outputlab','outputstore','outputother','totaloutput','endnum','workhour','remark');
  $i=1;
  while (isset($_POST["stepname".$i])) {
    $sql = "UPDATE curreport SET ";
    unset($conds);
    $conds = array();
    foreach ($cols as $value) {
      if (isset($_POST[$value.$i])) {
        $conds[]=$value."=".($_POST[$value.$i]==null?0:$_POST[$value.$i]);
      }
      else {
        $conds[]=$value."=".($_POST["h_".$value.$i]==null?0:$_POST["h_".$value.$i]);
      }
    }
    // 添加“改出序号”列，此列为字符串，与其他数据不同
    if (isset($_POST["outputid".$i])) {
      $conds[]="outputid"."='".($_POST["outputid".$i]==null?0:$_POST["outputid".$i])."'";
    }
    else {
      $conds[]="outputid"."='".($_POST["h_"."outputid".$i]==null?0:$_POST["h_"."outputid".$i])."'";
    }
    // 添加“产品名称”和“订单号”列，研发行可能修改这一列
    $conds[]="prodname"."='".($_POST["prodname".$i])."'";
    $conds[]="ordno"."='".($_POST["ordno".$i])."'";
    // 处理sql语句条件数组
    $condition = implode(',', $conds);
    $sql = $sql . $condition . " WHERE date='".($year.".".$month).
    "' AND id='".$_POST["h_id0"].
    "' AND stepname='".$_POST["stepname".$i]."'";

    doExecute($pdo, $sql);

    // 如果有改出序号，更新“改出序号”的小计行的“改入”和“结存”
    if (isset($_POST['outputid'.$i]) && strlen($_POST['outputid'.$i])>0) {
      $arrorder = explode("+",$_POST['outputid'.$i]);
      foreach ($arrorder as $value) {
        $temp = explode(".", $value);
        $sql = "UPDATE curreport SET inputchg=ifnull(inputchg,0)+".$temp[1].
          ", endnum=ifnull(endnum,0)+".$temp[1].
          " WHERE id='".$temp[0]."' and date='".($year.".".$month).
          "' and stepid=0";
        doExecute($pdo,$sql);
        // 更新“改出序号”的小计行的“改入序号”
        $sql = "SELECT isnull(inputid) AS num FROM curreport WHERE".
            " id='".$temp[0]."' and date='".($year.".".$month).
            "' and stepid=0";
        $num = getNumber($pdo, $sql);

        // 根据是否第一次改入，设置“改入序号”
        if ($num == 1) { // 第一次改入
          $val = $_POST['h_id0'].".".$temp[1];
          $sql = "UPDATE curreport SET inputid='".$val."' WHERE".
              " id='".$temp[0]."' and date='".($year.".".$month).
              "' and stepid=0";
        }
        else {
          $sql = "UPDATE curreport SET inputid=concat(inputid,'+".$val."') WHERE".
              " id='".$temp[0]."' and date='".($year.".".$month).
              "' and stepid=0";
        }
        doExecute($pdo, $sql);
      }  
    }
    $i+=1;
  }
}
// 处理AJAX请求，根据产品名返回订单号和对应的序号
if (isset($_GET['ajax'])&&isset($_GET['prodname'])) {
  if ($_GET['prodname'] != NULL) {
    $sql="SELECT * FROM curorders WHERE prodname like :prodname";
    $arr['prodname'] = '%'.$_GET['prodname'].'%';
    $result = getRows(doBindQuery($pdo, $sql, $arr));
    $response = array();
    foreach ($result as $value) {
      $response[] = $value['ordno']." -- ".$value['id'];
    }
    echo implode('|', $response);
  }
  exit();
}

// 获取totalreport本月信息
$sql = "SELECT * FROM totalreport WHERE date='".($year.".".$month)."'";
$arr_ttreport = getRows(doQuery($pdo, $sql));

$arr_ttrt = $arr_ttreport[0]; // 用于缩短代码长度

// 获取分页信息
  $cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $results_per_page = 1;
  $skip = ($cur_page - 1) * $results_per_page;
// 获得所有记录数
  $sql = "SELECT * FROM curreport WHERE date='".($year.".".$month)."' AND stepid=0 ORDER BY id";
  $result=doQuery($pdo, $sql);
  $total = $result->rowCount();
  $num_pages = ceil($total / $results_per_page);
  $sql = "SELECT * FROM curreport WHERE date='".($year.".".$month)."' AND id='".$cur_page."'";
  $arr_report = getRows(doQuery($pdo, $sql)); 

  $page_links = generate_page_links("repWri" ,$cur_page, $num_pages);

include 'v_repWri.html.php';
exit();
?>