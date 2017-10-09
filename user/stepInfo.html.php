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
    <h4>生产工序设置</h4>

    <div>
      <form action="?stepInfo" method="post" class="form-inline">
      <div class="row">
        <div class="form-group">
          <label for="stepname">工序名称：</label>
          <input type="text" name="stepname" id="stepname" class="form-control">
        </div>
        <div class="form-group">
          <input type="submit" name="addStep" value="添加" class="form-control btn-primary">
        </div>
      </form>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-condensed">
            <thead>
            <tr>
              <td align = 'center'>工序编号</td>
              <td align = 'center'>工序名称</td>
            </tr>
            </thead>
            <tbody>
              <?php 
              if (isset($arr_step)) {
                $i = 1;
                foreach ($arr_step as $value) {
                  echo "<tr>";
                  echo "<td>".$i."</td>";
                  echo "<td>".$value['stepname']."</td>";
                  //echo "<td align = 'center'><a href='?stepInfo&del&stepid=".$value['stepid']."'>删除</a></td>";
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
</body>
</html>