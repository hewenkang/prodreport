<!DOCTYPE html>
<!-- 用户首页 -->
<html lang="zh-cn">
<head>
  <meta charset="UTF-8">
  <title>欢迎使用晋西车轴生产月报系统</title>
  <?php include '../head.inc.html'; ?>
</head>
<body>
<?php include "../nav.html.inc.php"; ?>
  <div class="container-fluid">
    <?php if (isset($err_msg)) {
      echo "<div>";
      echo "<p>".$err_msg."</p>";
      echo "</div>";
    } ?>
    <h4><?php echo $year."年".$month."月"; ?>月报表格生成</h4>
    <div>
      <div class="row">
        <div class="col-md-10">
          <form action="?repCre" onsubmit="try{return validate_form(this);}catch(ex){return false;}" 
      method="post" class="form-inline">
            <div class="row">
              <!-- 订单号/令号（！需要JS格式验证） -->
              <div class="form-group">
                <label for="prodname">根据产品名称查询：</label>
                <input type="text" name="prodname" id="prodname" class="form-control" value="<?php echo $prodname; ?>">
                <label for="ordno">订单号/令号：</label>
                <select name="ordno" id="ordno" class="form-control">
                  <?php foreach ($arr_selorder as $value) {
                    echo "<option>".$value["val"]."</option>";
                  } ?>
                  
                </select>
                <label for="prodname2">产品名称：</label>
                <select name="prodname2" id="prodname2" class="form-control">
                  <?php foreach ($arr_selprodname as $value) {
                    echo "<option>".$value["val"]."</option>";
                  } ?>
                  
                </select>
              </div>
              <!-- 保存按钮 -->
              <div class="form-group">
                <input type="submit" name="addOrder" value="确定" class="form-control btn-primary">
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-2">
          <form action="?repCre" method="post" class="form-inline">
          <!-- 日报生成按钮 -->
            <div class="form-group">
              <input type="submit" name="creReport" id="creReport" value="生成本月日报" class="form-control btn-primary">
            </div>
          </form>
        </div>
      </div>
      
    </div>
    
    <br>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-condensed">
            <thead>
            <tr>
              <td align = 'center'>序号</td>
              <td align = 'center'>订单号/令号</td>
              <td align = 'center'>订单类别</td>
              <td align = 'center'>产品名称</td>
              <td align = 'center'>工序号</td>
              <td align = 'center'>工序名</td>
              <td align = 'center'>删除记录</td>
            </tr>
            </thead>
            <tbody>
              <?php 
              if (isset($arr_order)) {
                $i = 1;
                foreach ($arr_order as $value) {
                  echo "<tr>";
                  echo "<td>".$value['id']."</td>";
                  echo "<td>".$value['ordno']."</td>";
                  echo "<td>".$value['ordtype']."</td>";
                  echo "<td>".$value['prodname']."</td>";
                  echo "<td>".$value['stepids']."</td>";
                  echo "<td>".$value['stepnames']."</td>";
                  echo "<td><a href='?repCre&del=".$value['id']."'>删除</a></td>";
                  echo "</tr>";
                  $i += 1;
                }
              }
             ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php include '../foot.inc.html'; ?>
<script type="text/javascript">
// 验证表单数据项
  function validate_form(thisform)
  {
    with (thisform)
    {
      if(event){console.log(event);}
      if (ordno.value==null||ordno.value=='') {
        alert("请先填写“订单号”！");
        ordno.focus();
        return false;
      }
    }
  }

  window.onload = function (){
    var ordno = document.getElementById('ordno');
    ordno.focus();
    // 为“日报生成”按钮添加警告
    var btn_creReport = document.getElementById('creReport');
    btn_creReport.onclick = function (event){
      confirm('注意：日报生成后不能修改，是否立即生成本月日报？');
    }
  }
// 处理AJAX，提示“订单号/令号”
  var prod = document.getElementById('prodname');
  var ord = document.getElementById('ordno');
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange=stateChanged;

prod.onchange = function(){
  xhr.open('get','?repCre&ajax&prodname='+prod.value,true);
  xhr.send(null);
}
function stateChanged() 
{ 
  if (xhr.readyState==4 || xhr.readyState=="complete")
   {
      // 删除之前的订单记录
      while(ord.firstChild){
        ord.removeChild(ord.firstChild); 
      }
      // 添加新<option>
     var arr = xhr.responseText.split("|");
     var opt; 
     for(var i = 0; i < arr.length; ++i){
      opt = document.createElement("option");
      opt.appendChild(document.createTextNode(arr[i]));
      ord.appendChild(opt);
     }
     ord.onchange(); //
   } 
}
// 根据号 生成产品名称的AJAX
var prodname2 = document.getElementById('prodname2');
var xhr2 = new XMLHttpRequest();
  xhr2.onreadystatechange=stateChanged2;
ord.onchange = function () {
  xhr2.open('get','?repCre&ajax&ordno='+ord.value, true);
  xhr2.send(null);
}
function stateChanged2() 
{   
  if (xhr2.readyState==4 || xhr2.readyState=="complete")
   {
    console.log(xhr2.responseText);
      // 删除之前的订单记录
      while(prodname2.firstChild){
        prodname2.removeChild(prodname2.firstChild); 
      }
      // 添加新<option>
     var arr = xhr2.responseText.split("|");
     var opt; 
     for(var i = 0; i < arr.length; ++i){
      opt = document.createElement("option");
      opt.appendChild(document.createTextNode(arr[i]));
      prodname2.appendChild(opt);
     }

   } 
}
</script>
</body>
</html>