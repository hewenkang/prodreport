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
    <h4>部件编码设置</h4>
    <!-- 如果要添加的部件编码已存在，则显示一个错误提示 -->
    <div>
      <p>
        <?php if (isset($errmsg)) {
          echo $errmsg;
        } ?>
      </p>
    </div>

    <div>
      <form action="?comnMan" method="post" class="form-inline">
      <div class="row">
        <div class="form-group">
          <label for="proid">产品名称：</label>
          <select id="proid" name="proid" class="form-control">
          <?php 
          if (isset($arr_prod)) {
            foreach ($arr_prod as $value) {
              echo "<option value='".$value['proid']."'".
              (isset($proid)&&$value['proid']==$proid?" selected":"").">".$value['proname']."</option>";
            }
          }
           ?>
          </select>
        </div>
        <div class="form-group">
          <label for="comname">部件名称：</label>
          <input type="text" name="comname" id="comname" class="form-control">
        </div>
        <div class="form-group">
          <label for="comcode">部件编码：</label>
          <input type="text" name="comcode" id="comcode" class="form-control">
        </div>
        <div class="form-group">
          <input type="submit" value="添加" class="form-control btn-primary">
          <input type="hidden" name="addNewComn">
        </div>

      </form>
    </div>
    <br>
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-condensed" id="tb_comn">
        <thead>
        <tr>
          <td align = 'center'>部件编号</td>
          <td align = 'center'>部件名称</td>
          <td align = 'center'>部件编码</td>
          <td></td>
        </tr>
        </thead>
        <tbody>
          <?php 
          if (isset($arr_comn)) {
            $i = 1;
            foreach ($arr_comn as $value) {
              echo "<tr>";
              echo "<td>".$i."</td>";
              echo "<td>".$value['comname']."</td>";
              echo "<td>".$value['comcode']."</td>";
              echo "<td align = 'center'><a href='?comnMan&del&comid=".$value['comid'].
                "&proid=".$value['proid']."'>删除</a></td>";
              echo "</tr>";
              $i += 1;
            }
          }
         ?>
        </tbody>
      </table>
    </div>
  </div>
<?php include '../foot.inc.html'; ?>
<script type="text/javascript">
  var sel = document.getElementById('proid');
  var tb_comn = document.getElementById('tb_comn');
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange=stateChanged;

sel.onchange = function(){
  xhr.open('get','index.php?comnMan&ajax&proid='+sel.value,true);
  xhr.send(null);

}
function stateChanged() 
{ 
  if (xhr.readyState==4 || xhr.readyState=="complete")
   {
     var tbd = document.getElementById("tb_comn").childNodes.item(3);
     // 删除之前的部件记录
     var trs = tbd.getElementsByTagName("tr");
     for(var i = trs.length-1; i >= 0; --i){
       tbd.removeChild(trs[i]);
     }
     // 添加新记录
     var arr = xhr.responseText.split("|");
     var temp, tr; 
     for(var i = 0; i < arr.length -1; ++i){
      temp = arr[i].split(",");
      tr = document.createElement("tr");
      tr.innerHTML="<td>"+(i+1)+"</td><td>"+temp[2]+"</td><td>"+temp[3]+
        "</td><td align='center'><a href='?comnMan&del&comid="+temp[0]+"&proid="+temp[1]+"'>删除</td>";
      tbd.insertBefore(tr,tbd.lastChild);
     }
   } 
}
</script>
</body>
</html>