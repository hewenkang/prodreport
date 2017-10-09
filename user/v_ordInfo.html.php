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
  <div class="container">
    <h4>订单信息维护</h4>
    <div>
      <form action="?ordInfo" onsubmit="try{return validate_form(this);}catch(ex){return false;}" 
      method="post" class="form-inline">
        <div class="row">
          <!-- 订单号文本框 （！需要JS格式验证） -->
          <div class="form-group">
            <label for="ordno">订单号：</label>
            <input type="text" name="ordno" id="ordno" class="form-control">
          </div>
          <div class="form-group">
            <label for="ordtype">类别：</label>
            <select name="ordtype" id="ordtype" class="form-control"> 
              <option>车轴</option>
              <option>自营</option>
              <option>研发</option>
              <option>轮对</option>
            </select>
          </div>
        <!-- 产品名称文本框 （！需要替换成下拉列表） -->
          <div class="form-group">
            <label for="prodname">产品名称：</label>
            <input type="text" name="prodname" id="prodname" class="form-control">
          </div>
          <!-- 保存按钮 -->
          <div class="form-group">
            <input type="submit" name="addOrdInfo" value="保存" class="form-control btn-primary">
          </div>
        </div>
        <br>
        <div class="row">
          <!-- 产品工序多选框  -->
          <div class="form-group">
            <label>工序名称：</label>
            <?php 
            if (isset($arr_step)):
              foreach ($arr_step as $value):
             ?>
            <div class="checkbox">
              <label><input type="checkbox" name="stepid[]" value="<?php echo $value['stepid']?>" checked>
                <?php echo $value['stepname']?>
              </label>
            </div>
            <?php 
              endforeach;
            endif; 
            ?>
          </div> 
        </div>
      </form>
    </div>
    <br>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-condensed">
            <thead>
            <tr>
              <td align = 'center'>编号</td>
              <td align = 'center'>订单号</td>
              <td align = 'center'>订单类别</td>
              <td align = 'center'>产品名称</td>
              <td align = 'center'>工序号</td>
              <td align = 'center'>工序名</td>
              <td align = 'center'>添加时间</td>
              <td align = 'center'>删除</td>
            </tr>
            </thead>
            <tbody>
              <?php 
              if (isset($arr_order)) {
                $i = 1;
                foreach ($arr_order as $value) {
                  echo "<tr>";
                  echo "<td>".$i."</td>";
                  echo "<td>".$value['ordno']."</td>";
                  echo "<td>".$value['ordtype']."</td>";
                  echo "<td>".$value['prodname']."</td>";
                  echo "<td>".$value['stepids']."</td>";
                  echo "<td>".$value['stepnames']."</td>";
                  echo "<td>".$value['date']."</td>";
                  echo "<td><a href='?ordInfo&del=".$value['ordid']."'>删除</a></td>";
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
      if (ordno.value==null||ordno.value=='') {
        alert("请先填写“订单号”！");
        ordno.focus();
        return false;
      }
      if (prodname.value==null||prodname.value=='') {
        alert("请先填写“产品名称”！");
        prodname.focus();
        return false;
      }
    }
  }

</script>
</body>
</html>