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
    <h4>车辆定额导出</h4>
    <!-- 如果没选单位和产品类型，则显示一个错误提示 -->
    <div>
      <p>
        <?php if (isset($errmsg)) {
          echo $errmsg;
        } ?>
      </p>
    </div>

    <div>
      <form action="?traquoExp" method="post" class="form-inline">
      <div class="row">
        <div class="form-group">
          <label for="proid">产品名称：</label>
          <select name="proid" id="proid" class="form-control">
            <option value='all'>所有</option>
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
          <label for="comid">部件名称：</label>
          <select name="comid" id="comid" class="form-control">
            <option value='all'>所有</option>
            <?php 
            foreach($arr_comn as $row){
              echo "<option value='".$row['comid'].
              "' ".(isset($comid)&&$row['comid']===$comid?"selected":"").
              ">".$row['comname']."</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <input type="submit" name="search" value="查询" class="form-control btn-primary">
        </div>
        <div class="form-group">
          <label for="exported">
          <input type="checkbox" name="exported" id="exported" value="true" 
          <?php if (isset($exported)) {
            echo " checked";
          } ?>
          >
          包含已经导出过的定额信息
          </label>
        </div>
        
        <!-- 导出excel的按钮 -->
        <?php 
        if (isset($arr_quotas) && count($arr_quotas) != 0) {
           echo "<br><br><input type='submit' name='export' value='导出excel' class='btn btn-primary' >";
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
  </div>
<?php include '../foot.inc.html'; ?>
<script type="text/javascript">
  // 实现根据“产品名称”获得并显示“部件名称”的AJAX，因为功能和maPrice的相同，所以就直接用了代码，下面的URL也是maPrice
var sel_prod = document.getElementById('proid');
var xhr = new XMLHttpRequest();
xhr.onreadystatechange=stateChanged;
sel_prod.onchange = function(){
  xhr.open('get','index.php?maPrice&ajax&proid='+sel_prod.value,true);
  xhr.send(null);
}
function stateChanged() 
{ 
  if (xhr.readyState==4 || xhr.readyState=="complete")
   {
     var sel_comn = document.getElementById("comid");
     // 删除之前的部件记录
     var tnode = sel_comn.firstChild;
     for(var i = sel_comn.childNodes.length-1; i > 1; --i){
       sel_comn.removeChild(sel_comn.lastChild);
     }
     // 添加新记录
     var arr = xhr.responseText.split("|");
     var temp, tr; 
     for(var i = 0; i < arr.length -1; ++i){
      temp = arr[i].split(",");
      opt = document.createElement("option");
      opt.value = temp[0];
      opt.innerHTML=temp[1];
      sel_comn.appendChild(opt);
     }
   } 
}
</script>
</body>
</html>