<!DOCTYPE html>
<!-- 用户首页 -->
<html lang="zh-cn">
<head>
  <meta charset="UTF-8">
  <title>欢迎使用晋西车轴财务定额系统</title>
  <?php include '../head.inc.html'; ?>
</head>
<body>
<?php include "../nav.html.inc.php"; ?>
  <div class="container">
    <h4>车辆定额生成</h4>
    <!-- 如果没选单位和产品类型，则显示一个错误提示 -->
    <div>
      <p>
        <?php if (isset($errmsg)) {
          echo $errmsg;
        } ?>
        <?php 
          if (isset($errpage) && count($errpage) != 0) {
            $temp = implode($errpage, ",");
            echo "excel文件中第".$temp."行数据存在错误，请改正后重新导入<br>";
          } 
        ?>
      </p>
    </div>

    <div>
      <form action="?traquoCre" method="post" class="form-inline" 
      enctype="multipart/form-data">
      <div class="row">
        <div class="form-group">
          <label for="comid">公司名称：</label>
          <select name="comid" id="comid" class="form-control">
            <?php 
            foreach($arr_com as $row){
              echo "<option value='".$row['comid'].
              "' ".(isset($comid)&&$row['comid']===$comid?'selected':'').
              ">".$row['comname']."</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="proid">产品名称：</label>
          <select name="proid" id="proid" class="form-control">
            <?php 
            foreach($arr_prod as $row){
              echo "<option value='".$row['proid'].
              "' ".(isset($proid)&&$row['proid']===$proid?"selected":'').
              ">".$row['proname']."</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="outtype">出库类别：</label>
          <select name="outtype" id="outtype" class="form-control">
            <?php 
            foreach($arr_outtype as $row){
              echo "<option value='".$row['outtcode']."-".$row['outtname'].
              "' ".(isset($outtcode)&&$row['outtcode']."-".$row['outtname']===$outtcode?"selected":"").
              ">".$row['outtname']."</option>";
            }
            ?>
          </select>
        </div>

        <!-- EXCEL定额原始表格上传 -->
        <div class="form-group">
          EXCEL上传：选择文件（excel2003/2007, 小于2M）：
          <input type="file" name="excel" id="excel" >
          <input type="submit" value="确定上传" name="traquoCreate" >
        </div>
      </form>
      <!-- 导出excel的按钮 -->
      <form action="?export" method="post" class="form-inline" >
        <?php 
        if (isset($arr_quotas) && count($arr_quotas) != 0) {
           echo "<input type='submit' id='export' value='导出excel' class='btn btn-primary' >";
         } ?>
      </form>
    </div>
    <br>
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-condensed">
        <thead>
        <tr>
          <td>出库类别</td>
          <td>物品名称</td>
          <td>部件名称</td>
          <td>零件编码</td>
          <td>数/辆</td>
          <td>名称</td>
          <td>牌号及标准代号</td>
          <td>品种及标准代号</td>
          <td>下料尺寸</td>
          <td>单件</td>
          <td>合计</td>
          <td>单价</td>
          <td>总价</td>
        </tr>
        </thead>
        <tbody>
          <?php 
          if (isset($arr_quotas)) {
            foreach ($arr_quotas as $value) {
              echo "<tr>";
              echo "<td>".$value['outtype']."</td>";
              echo "<td>".$value['objname']."</td>";
              echo "<td>".$value['comname']."</td>";
              echo "<td>".$value['comcode']."</td>";
              echo "<td>".$value['number']."</td>";
              echo "<td>".$value['shape']."</td>";
              echo "<td>".$value['markcode']."</td>";
              echo "<td>".$value['typecode']."</td>";
              echo "<td>".$value['cutsize']."</td>";
              echo "<td>".$value['oneheavy']."</td>";
              echo "<td>".$value['totalhvy']."</td>";
              echo "<td>".$value['uprice']."</td>";
              echo "<td>".$value['tprice']."</td>";
              echo "</tr>";
            }
          }
         ?>
        </tbody>
      </table>
    </div>
    <!-- <div>
      <table class="quota-table">
        
        
      </table>
    </div> -->
  </div>
<?php include '../foot.inc.html'; ?>
</body>
</html>