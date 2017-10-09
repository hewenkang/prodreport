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
    <h4>工序地点设置</h4>
    <div>
      <form action="?siteInfo" method="post" class="form-inline">
      <div class="row">
      <!-- 工序名称下拉列表 -->
        <div class="form-group">
          <label for="stepid">工序名称：</label>
          <select name="stepid" id="stepid" class="form-control">
          <?php 
            if (isset($arr_step)) {
              foreach ($arr_step as $value) {
                echo "<option value='".$value['stepid']."' ".
                (isset($stepid) && $value['stepid']==$stepid ? "selected" : "").">".
                $value['stepname']."</option>";
              }
            }
           ?>
          </select>
        </div>
      <!-- 工序地点（加工方式）文本框 -->
        <div class="form-group">
          <label for="stepsite">工序地点 (加工方式)：</label>
          <input type="text" name="stepsite" id="stepsite" class="form-control">
        </div>
      <!-- “添加”按钮 -->
        <div class="form-group">
          <input type="submit" name="addStepSite" value="添加" class="form-control btn-primary">
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
              <td align = 'center'>记录编号</td>
              <td align = 'center'>工序名称</td>
              <td align = 'center'>工序地点 (加工方式)</td>
            </tr>
            </thead>
            <tbody>
              <?php 
              if (isset($arr_stepsite)) {
                $i = 1;
                foreach ($arr_stepsite as $value) {
                  echo "<tr>";
                  echo "<td>".$i."</td>";
                  echo "<td>".$value['stepname']."</td>";
                  echo "<td>".$value['stepsite']."</td>";
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