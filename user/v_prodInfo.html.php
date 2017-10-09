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
    <h4>产品信息维护</h4>
    <div>
      <form action="?prodInfo" onsubmit="try{return validate_form(this);}catch(ex){return false;}" 
      method="post" class="form-inline">
        <div class="row">
          <!-- 产品编码文本框 （！需要JS格式验证） -->
          <div class="form-group">
            <label for="prodcode">产品编码：</label>
            <input type="text" name="prodcode" id="prodcode" class="form-control">
          </div>
          <!-- 产品名称文本框 （！需要替换成下拉列表） -->
          <div class="form-group">
            <label for="prodname">产品名称：</label>
            <input type="text" name="prodname" id="prodname" class="form-control">
          </div>
          <!-- 产品代码文本框 （！需要替换成下拉列表） -->
          <div class="form-group">
            <label for="prodmark">产品代号：</label>
            <input type="text" name="prodmark" id="prodmark" class="form-control">
          </div>
          <!-- 保存按钮 -->
          <div class="form-group">
            <input type="submit" name="addProdInfo" value="保存" class="form-control btn-primary">
          </div>
        </div>
        <br>
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
              <td align = 'center'>产品编码</td>
              <td align = 'center'>产品名称</td>
              <td align = 'center'>产品代号</td>
              <td align = 'center'>删除</td>
            </tr>
            </thead>
            <tbody>
              <?php 
              if (isset($arr_prod)) {
                foreach ($arr_prod as $value) {
                  echo "<tr>";
                  echo "<td>".$value['prodid']."</td>";
                  echo "<td>".$value['prodcode']."</td>";
                  echo "<td>".$value['prodname']."</td>";
                  echo "<td>".$value['prodmark']."</td>";
                  echo "<td><a href='?prodInfo&del=".$value['prodid']."'>删除</a></td>";
                  echo "</tr>";
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
      // if (prodcode.value==null||prodcode.value=='') {
      //   alert("请填写“产品编码”！");
      //   prodcode.focus();
      //   return false;
      // }
      if (prodname.value==null||prodname.value=='') {
        alert("请填写“产品名称”！");
        prodname.focus();
        return false;
      }
      if (prodmark.value==null||prodmark.value=='') {
        alert("请填写“产品代号”！");
        prodmark.focus();
        return false;
      }
    }
  }

</script>
</body>
</html>