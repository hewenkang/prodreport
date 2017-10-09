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
    <h4>材料单价填报</h4>
    <!-- 如果没选单位和产品类型，则显示一个错误提示 -->
    <div>
      <p>
        <?php if (isset($errmsg)) {
          echo $errmsg;
        } ?>
        <?php 
          if (isset($errpage) && count($errpage) != 0) {
            $temp = implode($errpage, ",");
            echo "excel文件中第".$temp."行数据存在错误，请改正后单独导入<br>";
          } 
        ?>
      </p>
    </div>
  
    <div>
      <form action="?maPrice" method="post" class="form-inline">
      <div class="row">
        <div class="form-group">
          <label for="proid">产品名称：</label>
          <select id="proid" name="proid" class="form-control">
            <option value='all'>所有</option>
            <?php 
            foreach ($arr_prod as $row) {
              echo "<option value='".$row['proid']."' ".
              ($row['proid']==$proid?"selected":"").">".
              $row['proname']."</option>";
            }
             ?>
          </select>
        </div>
        <div class="form-group">
          <label for="comid">部件名称：</label>
          <select id="comid" name="comid" class="form-control">
            <option value='all'>所有</option>
            <?php 
            foreach ($arr_comn as $row) {
              echo "<option value='".$row['comid']."' ".
              (isset($comid)&&$row['comid']==$comid?"selected":"").">".
              $row['comname']."</option>";
            }
             ?>
          </select>
        </div>
        <div class="form-group">
          <input class="btn btn-primary" type="submit" name="search" value="查询">
        </div>
        <div></div><br>
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-condensed">
            <thead>
            <tr>
              <td>物品名称</td>
              <td>部件名称</td>
              <td>数/辆</td>
              <td>名称</td>
              <td>牌号及标准代号</td>
              <td>品种及标准代号</td>
              <td>单价</td>
              <td>总价</td>
            </tr>
            </thead>
            <tbody>
              <?php 
              if (isset($arr_quotas)&&count($arr_quotas)!=0) {
                $i = 1;
                foreach ($arr_quotas as $value) {
                  echo "<tr>";
                  echo "<td>".$value['objname']."</td>";
                  echo "<td>".$value['comname']."</td>";
                  echo "<td id='number".$i."'>".$value['number']."</td>";
                  echo "<td id='shape".$i."'>".$value['shape']."</td>";
                  echo "<td id='markcode".$i."'>".$value['markcode']."</td>";
                  echo "<td id='typecode".$i."'>".$value['typecode']."</td>";
                  echo "<td id='uprice".$i."'><input type='text' name='uprice".$i."' value='".$value['uprice']."'></td>";
                  echo "<td id='tprice".$i."'><input type='text' name='tprice".$i."' value='".$value['tprice']."'></td>";
                  echo "</tr>";
                  echo "<input type='hidden' name='quotaid".$i."' value='".$value['quotaid']."'>";
                  $i += 1;
                }
              }
             ?>
            </tbody>
          </table>
        </div>
        <div>
          <input type="submit" name="save" value="保存" class="btn btn-primary">
          <input type="submit" name="submit" value="提交" class="btn btn-primary">
        </div>
        
      </form>
    </div>
    
  </div>
<?php include '../foot.inc.html'; ?>

<script type="text/javascript">
//
var tds = document.getElementsByTagName("td");
var regw = /[a-z]*/; // reg word
var regd = /\d+/; // reg digit
for(var i=0; i<tds.length; ++i){
  if (tds[i].getAttribute("id")!=null) {
    if (regw.exec(tds[i].getAttribute("id"))[0] == "uprice") {
      tds[i].firstChild.onchange = function(){
        // 检查数据是否合法
        if (isNaN(Number(this.value)) || Number(this.value)<0) {
            alert('此项应填表示价格的浮点数');
            this.focus();
        }
        else {
          // 数据合法，开始处理
          var num = regd.exec(this.parentNode.getAttribute("id"))[0];
          // 根据单价生成总价
          t = document.getElementById("tprice"+num);
          n = document.getElementById("number"+num);
          t.firstChild.value =  this.value * n.firstChild.data;
          // 为之后相同名称规格型号的材料添加单价和总价
          var shape = document.getElementById("shape"+num).innerHTML;
          var markcode = document.getElementById("markcode"+num).innerHTML;
          var typecode = document.getElementById("typecode"+num).innerHTML;
          var i = Number(num) + 1;
          while(document.getElementById("uprice"+i)!= null) {
            var s = document.getElementById("shape"+i);
            var m = document.getElementById("markcode"+i);
            var t = document.getElementById("typecode"+i);
            var tprice = document.getElementById("tprice"+i);
            var number = document.getElementById("number"+i);
            if (s.innerHTML==shape && m.innerHTML==markcode && t.innerHTML==typecode) {
              document.getElementById("uprice"+i).firstChild.value = this.value;
              tprice.firstChild.value = this.value * number.firstChild.data;
            }
            i += 1;
          } // 为相同规格型号添加价格结束
          // 设置焦点，从上到下检查，每一个值为空的uprice
          i = 1;
          temp = document.getElementById('uprice'+i);
          console.log(i+":"+temp.firstChild.value);
          while(temp != null) {
            console.log("obj exists");
            if (temp.firstChild.value == "") {
              console.log("value is null, focus");
              temp.firstChild.focus();
              break;
            }
            else {
              console.log(temp.getAttribute("id")+"value not null, it is"+temp.firstChild.value);
              i += 1;
              temp = document.getElementById('uprice'+i);
            }  
          } // 结束设置焦点
        } // 整个if else 结束 
      } // onchange结束
    }
  }
}


// 实现根据“产品名称”获得并显示“部件名称”的AJAX
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