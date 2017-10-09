<!DOCTYPE html>
<!-- 用户首页 -->
<html lang="zh-cn">
<head>
  <meta charset="UTF-8">
  <title>欢迎使用晋西车轴生产月报系统</title>
  <?php include '../head.inc.html'; ?>
  <link rel="stylesheet" href="../includes/prodreport.css">
</head>
<body>
<?php include "../nav.html.inc.php"; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <h4><?php echo $year."年".$month."月生产日报"; ?></h4>
      </div>
      <div class="col-md-3">
        <div class="form-group form-inline">
          <label for="prodname">订单查询：</label>
          <input type="text" id="prodname" class="form-control">
        </div> 
      </div>
    </div>
  </div>
    <div class="table-responsive"> 
      <form action="?repWri&page=<?php echo $arr_report[0]['id'] ?>" id="curreport" name="curreport" method="post">
        <table class="table table-bordered table-hover table-condensed"> 
          <thead>
          <tr>
            <td colspan="7" align="center">产品</td>
            <td colspan="6" align="center">本期投入</td>
            <td colspan="1" align="center">产出</td>
            <td colspan="3" align="center">废品</td>
            <td colspan="6" align="center">本期发出</td>
            <td colspan="1" align="center" nowrap>期末</td>
            <td colspan="2" align="center">其中：</td>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td>序号</td>
              <td nowrap>产品<br>编码</td>
              <td>产品<br>名称</td>
              <td>订单号<br>/令号</td>
              <td>类别</td><td>工序</td>
              <td>代号</td>
              
              <td>期初</td><td>投入</td><td>领入</td><td>改入</td><td nowrap>改入<br>序号</td><td>小计</td>
              <td>合格</td>
              <td>工废</td><td>料废</td><td>小计</td>
              <td>小计</td><td>下道</td><td>试验</td><td>入库</td><td>改出</td><td nowrap>改出<br>序号</td>
              <td>结存</td>
              <td>工时</td><td>备注</td>
            </tr>
            <!-- 总计行，每页显示，用$arr_ttreport -->
            <tr>
              <td>0</td> <!-- 序号 -->
              <td></td> <!-- 产品编码 -->
              <td></td> <!-- 产品名称 -->
              <td></td> <!-- 订单号/令号 -->
              <td id="ordtype"></td> <!-- 类别 -->
              <td>总计</td> <!-- 工序 -->
              <td></td> <!-- 代号 -->   
              
              <!-- 本期投入 -->
              <td id="startnum"><?php echo $arr_ttrt['startnum']; ?>
                <input type="hidden" id='h_startnum' name='h_startnum' value='<?php echo $arr_ttrt['startnum']?>'>
              </td> 
              <td id="inputnew"><?php echo $arr_ttrt['inputnew'] ?>
                <input type="hidden" id='h_inputnew' name='h_inputnew' value='<?php echo $arr_ttrt['inputnew']?>'>
              </td>
              <td id="inputget"><?php echo $arr_ttrt['inputget'] ?>
                <input type="hidden" id='h_inputget' name='h_inputget' value='<?php echo $arr_ttrt['inputget']?>'>
              </td>
              <td id="inputchg"><?php echo $arr_ttrt['inputchg'] ?>
                <input type="hidden" id='h_inputchg' name='h_inputchg' value='<?php echo $arr_ttrt['inputchg']?>'>
              </td>
              <td></td> <!-- 改入序号，不填 -->
              <td id="totalinput" style='background-color:#f5f5f5'><?php echo $arr_ttrt['totalinput'] ?>
                <input type="hidden" id='h_totalinput' name='h_totalinput' value='<?php echo $arr_ttrt['totalinput'] ?>'>
              </td>
              <td></td>   <!-- 合格 -->
              <td id="wscrap"><?php echo $arr_ttrt['wscrap'] ?>
                <input type="hidden" id='h_wscrap' name='h_wscrap' value='<?php echo $arr_ttrt['wscrap'] ?>'>
              </td> <!-- 废品 -->
              <td id="mscrap"><?php echo $arr_ttrt['mscrap'] ?>
                <input type="hidden" id='h_mscrap' name='h_mscrap' value='<?php echo $arr_ttrt['mscrap'] ?>'>
              </td>
              <td id="totalscrap" style='background-color:#f5f5f5'><?php echo $arr_ttrt['totalscrap'] ?>
                <input type="hidden" id='h_totalscrap' name='h_totalscrap' value='<?php echo $arr_ttrt['totalscrap'] ?>'>
              </td>
              <td id="totaloutput" style='background-color:#f5f5f5'><?php echo $arr_ttrt['totaloutput'] ?>
                <input type="hidden" id='h_totaloutput' name='h_totaloutput' value='<?php echo $arr_ttrt['totaloutput'] ?>'>
              </td> <!-- 本期发出 -->
              <td id="outputnext">
                <input type="hidden" id='h_outputnext' name='h_outputnext' value='<?php echo $arr_ttrt['outputnext'] ?>'>
              </td>
              <td id="outputlab"><?php echo $arr_ttrt['outputlab'] ?>
                <input type="hidden" id='h_outputlab' name='h_outputlab' value='<?php echo $arr_ttrt['outputlab'] ?>'>
              </td>
              <td id="outputstore"><?php echo $arr_ttrt['outputstore'] ?>
                <input type="hidden" id='h_outputstore' name='h_outputstore' value='<?php echo $arr_ttrt['outputstore'] ?>'>
              </td>
              <td id="outputother"><?php echo $arr_ttrt['outputother'] ?>
                <input type="hidden" id='h_outputother' name='h_outputother' value='<?php echo $arr_ttrt['outputother'] ?>'>
              </td>
              <!-- 改出序号，不填 -->
              <td></td> 
              <!-- 结存 -->
              <td id="endnum"><?php echo $arr_ttrt['endnum'] ?>
                <input type="hidden" id='h_endnum' name='h_endnum' value='<?php echo $arr_ttrt['endnum'] ?>'>
              </td> <!-- 其中： -->
              <td id="workhour"><?php echo $arr_ttrt['workhour'] ?>
                <input type="hidden" id='h_workhour' name='h_workhour' value='<?php echo $arr_ttrt['workhour'] ?>'>
              </td> 
              <td></td> <!-- 备注，不填 -->
            </tr>
            <?php 
              if (isset($arr_report)) {
                // 显示每个订单的“小计”行  
                foreach ($arr_report as $key => $value) {
                  $id2=$value['id2'];
                  if ($value['stepid']==0) {
                    // 小计行
                    echo "<tr>".
                    "<td id='id0'>". // 序号
                    $value['id']. 
                    "<input type='hidden' id='h_id".$id2."' name='h_id".$id2."' value='".$value['id'].
                    "'></td>". 
                    "<td id='prodcode0' nowrap title='".$value['prodcode']."'>". // 产品编码
                    substr($value['prodcode'],0,5). 
                    "..<input type='hidden' id='h_prodcode".$id2."' name='h_prodcode".$id2."' value='".$value['prodcode'].
                    "'></td>". 
                    "<td id='prodname0' nowrap>". // 产品名称
                    $value['prodname']. 
                    "<input type='hidden' id='h_prodname".$id2."' name='h_prodname".$id2."' value='".$value['prodname'].
                    "'></td>". 
                    "<td id='ordno0' nowrap>". // 订单号/令号
                    $value['ordno'].
                    "<input type='hidden' id='h_ordno".$id2."' name='h_ordno".$id2."' value='".$value['ordno'].
                    "'></td>".  
                    "<td id='ordtype0' nowrap>". // 类别
                    $value['ordtype'].
                    "<input type='hidden' id='h_ordtype".$id2."' name='h_ordtype".$id2."' value='".$value['ordtype'].
                    "'></td>".  
                    "<td id='stepname0'>". // 工序
                    $value['stepname'].
                    "<input type='hidden' id='h_stepname".$id2."' name='h_stepname".$id2."' value='".$value['stepname'].
                    "'></td>". 
                    "<td>". // 代号
                    $value['prodmark'].
                    "</td>".
                    "<td id='startnum0'>".  // 期初
                    $value['startnum'].
                    "<input type='hidden' id='h_startnum".$id2."' name='h_startnum".$id2."' value='".$value['startnum'].
                    "'></td>".
                    "<td id='inputnew0'>". // 投入
                    $value['inputnew'].
                    "<input type='hidden' id='h_inputnew".$id2."' name='h_inputnew".$id2."' value='".$value['inputnew'].
                    "'></td>". 
                    "<td id='inputget0'>". // 领入
                    $value['inputget'].
                    "<input type='hidden' id='h_inputget".$id2."' name='h_inputget".$id2."' value='".$value['inputget'].
                    "'></td>". 
                    "<td id='inputchg0'>". // 改入
                    $value['inputchg'].
                    "<input type='hidden' id='h_inputchg".$id2."' name='h_inputchg".$id2."' value='".$value['inputchg'].
                    "'></td>". 
                    "<td id='inputid0'>".
                    $value['inputid']. // 改入序号
                    "<input type='hidden' id='h_inputid".$id2."' name='h_inputid".$id2."' value='".$value['inputid'].
                    "'></td>". 
                    "<td id='totalinput0' style='background-color:#f5f5f5'>". // 投入小计
                    $value['totalinput']. 
                    "<input type='hidden' id='h_totalinput".$id2."' name='h_totalinput".$id2."' value='".$value['totalinput'].
                    "'></td>". 
                    "<td id='qualified0'>". // 产出合格
                    $value['qualified'].
                    "<input type='hidden' id='h_qualified".$id2."' name='h_qualified".$id2."' value='".$value['startnum'].
                    "'></td>".
                    "<td id='wscrap0'>". // 工废
                    $value['wscrap'].
                    "<input type='hidden' id='h_wscrap".$id2."' name='h_wscrap".$id2."' value='".$value['wscrap'].
                    "'></td>". 
                    "<td id='mscrap0'>". // 料废
                    $value['mscrap'].
                    "<input type='hidden' id='h_mscrap".$id2."' name='h_mscrap".$id2."' value='".$value['mscrap'].
                    "'></td>". 
                    "<td id='totalscrap0' style='background-color:#f5f5f5'>". // 废品小计
                    $value['totalscrap'].
                    "<input type='hidden' id='h_totalscrap".$id2."' name='h_totalscrap".$id2."' value='".$value['totalscrap'].
                    "'></td>". 
                    
                    "<td id='totaloutput0' style='background-color:#f5f5f5'>". // 发出小计
                    $value['totaloutput']. 
                    "<input type='hidden' id='h_totaloutput".$id2."' name='h_totaloutput".$id2."' value='".$value['totaloutput'].
                    "'></td>". 
                    "<td id='outputnext0'>". // 下道，不累加求和，故此处不显示 $value['outputnext']
                    "<input type='hidden' id='h_outputnext".$id2."' name='h_outputnext".$id2."' value='".$value['outputnext'].
                    "'></td>". 
                    "<td id='outputlab0'>". // 实验
                    $value['outputlab'].
                    "<input type='hidden' id='h_outputlab".$id2."' name='h_outputlab".$id2."' value='".$value['outputlab'].
                    "'></td>". 
                    "<td id='outputstore0'>". // 入库
                    $value['outputstore'].
                    "<input type='hidden' id='h_outputstore".$id2."' name='h_outputstore".$id2."' value='".$value['outputstore'].
                    "'></td>". 
                    "<td id='outputother0'>" // 改出
                    .$value['outputother'].
                    "<input type='hidden' id='h_outputother".$id2."' name='h_outputother".$id2."' value='".$value['outputother'].
                    "'></td>". 
                    "<td id='outputid0'>". // 改出序号
                    $value['outputid'].
                    "<input type='hidden' id='h_outputid".$id2."' name='h_outputid".$id2."' value='".$value['outputid'].
                    "'></td>". 
                    "<td id='endnum0'>". // 结存
                    $value['endnum'].
                    "<input type='hidden' id='h_endnum".$id2."' name='h_endnum".$id2."' value='".$value['endnum'].
                    "'></td>". 
                    "<td id='workhour0'>". // 工时
                    $value['workhour'].
                    "<input type='hidden' id='h_workhour".$id2."' name='h_workhour".$id2."' value='".$value['workhour'].
                    "'></td>". 
                    "<td id='remark0'>". // 备注
                    $value['remark'].
                    "<input type='hidden' id='h_remark".$id2."' name='h_remark".$id2."' value='".$value['remark'].
                    "'></td>". 
                    "</tr>";
                  }
                  else {
                    echo "<tr id='tr".$value['stepsiteid']."'>"; 
                    echo "<td name='id".$id2."' id='id".$id2."'></td>"; // 序号，这里加id是为研发行提供num
                    echo "<td></td>"; // 产品编码
                    // 产品名称
                    echo "<td><input type='hidden' name='prodname".$id2."' id='prodname".$id2."' value='".$value['prodname']."'/></td>"; 
                    // 订单号
                    echo "<td><input type='hidden' name='ordno".$id2."' id='ordno".$id2."' value='".$value['ordno']."'/></td>";
                    // 类别
                    echo "<td><input type='hidden' name='ordtype".$id2."' id='ordtype".$id2."' value='".$value['ordtype']."' /></td>";
                    // 工序
                    echo "<td nowrap><input type='hidden' name='stepname".$id2."' id='stepname".$id2."' value='".$value['stepname']."'/>".
                    $value['stepname'].
                    "<input type='hidden' name='stepsiteflag".$id2."' id='stepsiteflag".$id2."' value='".$value['stepname'].
                    "'/></td>";
                    // 代号
                    echo "<td></td>"; 
                    
                    // 真正的数据开始，本期投入
                    echo "<td><input type='text' class='inputnum' name='startnum".$id2."' id='startnum".$id2.
                    "' value='".($value['startnum']==0?"":$value['startnum'])."'/></td>";
                    echo "<td><input type='text' class='inputnum' name='inputnew".$id2."' id='inputnew".$id2.
                    "' value='".($value['inputnew']==0?"":$value['inputnew'])."'/></td>";
                    echo "<td><input type='text' class='inputnum' name='inputget".$id2."' id='inputget".$id2.
                    "' value='".($value['inputget']==0?"":$value['inputget'])."'/></td>";
                    echo "<td><input type='text' class='inputnum' name='inputchg".$id2."' id='inputchg".$id2.
                    "' value='".($value['inputchg']==0?"":$value['inputchg'])."'/></td>";
                    // 改入序号
                    echo "<td>".$value['inputid']."</td>";
                    echo "<td style='background-color:#f5f5f5' id='totalinput".$id2."'>".($value['totalinput']==0?"":$value['totalinput'])."<input type='hidden' id='h_totalinput".$id2."' name='h_totalinput".$id2."' value='".$value['totalinput']."'/></td>";
                    // 合格
                    echo "<td><input type='text' class='inputnum' name='qualified".$id2."' id=‘qualified".$id2.
                    "' value='".($value['qualified']==0?"":$value['qualified'])."'/></td>";
                    // 废品
                    echo "<td><input type='text' class='inputnum' name='wscrap".$id2."' id='wscrap".$id2."' value='".($value['wscrap']==0?"":$value['wscrap'])."'/></td>";
                    echo "<td><input type='text' class='inputnum' name='mscrap".$id2."' id='mscrap".$id2."' value='".($value['mscrap']==0?"":$value['mscrap'])."'/></td>";
                    echo "<td style='background-color:#f5f5f5' id='totalscrap".$id2."'>".($value['totalscrap']==0?"":$value['totalscrap'])."<input type='hidden' id='h_totalscrap".$id2."' name='h_totalscrap".$id2."' value='".$value['totalscrap']."'/></td>";
                    // 本期发出
                    echo "<td style='background-color:#f5f5f5' id='totaloutput".$id2."'>".($value['totaloutput']==0?"":$value['totaloutput'])."<input type='hidden' id='h_totaloutput".$id2."' name='h_totaloutput".$id2."' value='".$value['totaloutput']."' /></td>";
                    echo "<td><input type='text' class='inputnum' name='outputnext".$id2."' id='outputnext".$id2."' value='".($value['outputnext']==0?"":$value['outputnext'])."'/></td>";
                    echo "<td><input type='text' class='inputnum' name='outputlab".$id2."' id='outputlab".$id2."' value='".($value['outputlab']==0?"":$value['outputlab'])."'/></td>";
                    echo "<td><input type='text' class='inputnum' name='outputstore".$id2."' id='outputstore".$id2."' value='".($value['outputstore']==0?"":$value['outputstore'])."'/></td>";
                    echo "<td><input type='text' class='inputnum' name='outputother".$id2."' id='outputother".$id2."' value='".($value['outputother']==0?"":$value['outputother'])."'/></td>";
                    echo "<td><input type='text' class='inputid' name='outputid".$id2."' id='outputid".$id2."' value='".($value['outputid']==0?"":$value['outputid'])."'/></td>";
                    // 期末
                    echo "<td id='endnum".$id2."'>".($value['endnum']==0?"":$value['endnum'])."<input type='hidden' id='h_endnum".$id2."' name='h_endnum".$id2."' value='".$value['endnum']."' /></td>";
                    // 其中
                    echo "<td><input type=text' class='inputnum'name='workhour".$id2."' id='workhour".$id2."' value='".($value['workhour']==0?"":$value['workhour'])."'/></td>";
                    echo "<td><input type=text' class='inputnum'name='remark".$id2."' id='remark".$id2."' value='".($value['remark']==0?"":$value['remark'])."'/>".
                      "<input type='hidden' id='checknum".$id2."' name='checknum".$value['id2']."' value='".$value['checknum']."'/>".
                      "<input type='hidden' id='checknum2".$id2."' name='checknum2".$value['id2']."' value='".$value['checknum']."'/>".
                      "</td>";
                    echo "</tr>";
                  }
                }
              }
             ?>
          </tbody>
        </table>
        <input type='submit' name='savereport' value='保存' class="btn btn-primary">
      </form>
    </div>
    <div><?php echo $page_links; ?></div>
  </div>
<?php include '../foot.inc.html'; ?>
<script type="text/javascript">
window.onload = function () {
  var zinput, h_zinput, xinput, h_xinput; // 代表“纵向总计、纵向小计”
  var zxinput, h_zxinput; // 代表“总计行中的小计”
  var xxinput, h_xxinput; // 代表“小计行中的小计”
  var totalinput, h_totalinput; // 代表“水平方向的小计”

  var endnum, h_endmun; // 代表“期末结存”
  var h_tinput,h_tscrap,h_toutput; // 代表“投入、废品、发出”的小计，计算“期末结存”用
  var olddata = 0; // 记录旧值，在onchange时从总计和小计中减去

  var e;
  var val;
  var regabc = /[a-z]+/; // 用于从id中字符串，生成纵向小计的id
  var regnum = /[0-9]+/; // 用于从id中提取数据，生成横向小计的id 
  var columns = ['startnum','inputnew','inputget','inputchg',
  'wscrap','mscrap',
  'outputnext','outputlab','outputstore','outputother'];
  for(var key in columns){
    var column = columns[key];
    var i = 1;
    while(document.getElementById(column+i)){
      e = document.getElementById(column+i);
      i+=1;
      e.onclick = function () {
        olddata = Number(this.value);
      }
      e.onselect = function () {
        olddata = Number(this.value);
      }
      e.onchange = function () {
        // 1. 所有项都修改“横向小计”对象
        // 根据id不同，选择不同的“横向小计”对象
        var str = "totalscrap";
        switch (this.id.substr(0,1)) {
          case "i": case "s":
            str = "totalinput";
            break;
          case "o":
            str = "totaloutput";
        };
        // 获取“横向小计”对象
        totalinput = document.getElementById(str+(regnum.exec(this.id))[0]);
        h_totalinput = document.getElementById("h_"+str+(regnum.exec(this.id))[0]);
        // 修改“横向小计”对象
        val = Number(h_totalinput.value) - olddata + Number(this.value);
        chgvalue(totalinput, val); 
        chgvalue(h_totalinput, val);
        // 2. 根据“横向小计”对象修改“横向结存”对象
        // 获取“横向结存”对象
        endnum = document.getElementById("endnum"+(regnum.exec(this.id))[0]);
        h_endnum = document.getElementById("h_endnum"+(regnum.exec(this.id))[0]);
        // 修改“横向结存”对象
        h_tinput  = document.getElementById("h_totalinput"+(regnum.exec(this.id))[0]);
        h_tscrap  = document.getElementById("h_totalscrap"+(regnum.exec(this.id))[0]);
        h_toutput  = document.getElementById("h_totaloutput"+(regnum.exec(this.id))[0]);
        val = Number(h_tinput.value) - Number(h_tscrap.value) - Number(h_toutput.value);
        chgvalue(endnum, val); 
        chgvalue(h_endnum, val);

        // 3. 有些项还需要修改“纵向小计”对象，同时修改小计和总计行的“小计”、“结存”
        switch ((regabc.exec(this.id))[0]) {
          case "inputnew": 
            if (regnum.exec(this.id)[0]!="1") {break;}
          case "startnum": 
          case "inputget": 
          case "inputchg": 
          case "wscrap":
          case "mscrap":
          case "outputlab":
          case "outputstore":
          case "outputother":
          // 获取“纵向总计”对象
          zinput = document.getElementById((regabc.exec(this.id))[0]);
          h_zinput = document.getElementById("h_"+(regabc.exec(this.id))[0]);
          // 修改“纵向总计”对象
          zinput.firstChild.nodeValue = Number(zinput.firstChild.nodeValue) - olddata + Number(this.value);
          h_zinput.value = Number(h_zinput.value) - olddata + Number(this.value);
          // 获取“纵向小计”对象
          xinput = document.getElementById((regabc.exec(this.id))[0]+"0");
          h_xinput = document.getElementById("h_"+(regabc.exec(this.id))[0]+"0");
          // 修改“纵向小计”对象
          val = Number(h_xinput.value) - olddata + Number(this.value);
          chgvalue(xinput, val); 
          chgvalue(h_xinput, val);

          // 获取总计行中的“小计”对象
          zxinput = document.getElementById(str);
          h_zxinput = document.getElementById("h_"+str);
          // 修改总计行中的“小计”对象
          val = Number(h_zxinput.value) - olddata + Number(this.value);
          chgvalue(zxinput, val); 
          chgvalue(h_zxinput, val);
          // 获取小计行中的“小计”对象
          xxinput = document.getElementById(str+'0');
          h_xxinput = document.getElementById("h_"+str+'0');
          // 修改小计行中的“小计”对象
          val = Number(h_xxinput.value) - olddata + Number(this.value);
          chgvalue(xxinput, val); 
          chgvalue(h_xxinput, val);

          // 修改“结存小计”对象
          endnum = document.getElementById("endnum0");
          h_endnum = document.getElementById("h_endnum0");
          h_tinput  = document.getElementById("h_totalinput0");
          h_tscrap  = document.getElementById("h_totalscrap0");
          h_toutput  = document.getElementById("h_totaloutput0");
          val = Number(h_tinput.value) - Number(h_tscrap.value) - Number(h_toutput.value);
          chgvalue(endnum, val); 
          chgvalue(h_endnum, val);
          // 修改“结存总计”对象
          endnum = document.getElementById("endnum");
          h_endnum = document.getElementById("h_endnum");
          h_tinput  = document.getElementById("h_totalinput");
          h_tscrap  = document.getElementById("h_totalscrap");
          h_toutput  = document.getElementById("h_totaloutput");
          val = Number(h_tinput.value) - Number(h_tscrap.value) - Number(h_toutput.value);
          chgvalue(endnum, val); 
          chgvalue(h_endnum, val);
        }

        if ((regabc.exec(this.id))[0]=="outputother") {
          zinput = document.getElementById("inputchg")
          h_zinput = document.getElementById("h_inputchg");
          val = Number(h_zinput.value) - olddata + Number(this.value);
          chgvalue(zinput, val); 
          chgvalue(h_zinput, val);
          zxinput = document.getElementById("totalinput")
          h_zxinput = document.getElementById("h_totalinput");
          val = Number(h_zxinput.value) - olddata + Number(this.value);
          chgvalue(zxinput, val); 
          chgvalue(h_zxinput, val);
        }
      } // end e.onchange
    } // end while
  }
  ///////////////////////////////////////////////////////////
  // "粗车外协、径向探伤、喷漆、滚压"只保留“投入”项可输入
  var trnum = [12, 13, 16, 17, 20];
  for(var i in trnum){
    if (document.getElementById("tr"+trnum[i])) {
      e = document.getElementById("tr"+trnum[i]);
      var inputs = e.getElementsByTagName("input");
      var num =regnum.exec(inputs[0].id)[0];
      var disablecolumn = ['startnum','inputget','inputchg',
          'wscrap','mscrap',
          'outputlab','outputstore','outputother','outputid'];
      for(var j in disablecolumn){
        var item = disablecolumn[j];
        inputs.namedItem(item+num).readOnly = true;
      } 
    } 
  }
  function chgvalue(node, val) {
    if (node.nodeName=="TD") {
      if (node.firstChild.nodeName != "#text") {
        var temp = document.createTextNode(val);
        node.insertBefore(temp, node.firstChild);
      }
      else {
        node.firstChild.nodeValue = val;
      }
      return;
    };
    if (node.nodeName=="INPUT") {
      node.value = val;
    };
  }
  ///////////////////////////////////////////////////////////
  // 打开“研发”行的“产品名称”项和“订单”项
  var tr = document.getElementById("tr21");
  var num = (regnum.exec(tr.firstElementChild.id))[0];
  var input = document.getElementById("prodname"+num);
  input.setAttribute("type","text");
  input.size = input.value.length;
  input = document.getElementById("ordno"+num);
  input.setAttribute("type","text");
  input.size = input.value.length
  
  ///////////////////////////////////////////////////////////
  // ajax查询订单号对应序号
  var prodname = document.getElementById("prodname");
  prodname.setAttribute("autocomplete","off");
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange=stateChanged;
  // 输入框有输入时触发ajax
  prodname.onkeyup = function (event) {
    // event.preventDefault();
    xhr.open('get','?repWri&ajax&prodname='+prodname.value,true);
    xhr.send(null);
  }
  // 输入框失去焦点时删除列表
  prodname.onblur = function () {
    if (prodname.nextElementSibling) {
        prodname.parentNode.removeChild(prodname.nextElementSibling);
    }
  }
  function stateChanged() 
  { 
    if (xhr.readyState==4 || xhr.readyState=="complete")
    {
      var div = document.createElement("div");
      div.style.position = "relative";
      
      var div2 = document.createElement("div");
      div2.style.position = "absolute";
      div2.style.left = "70px";
      div2.style.padding = "10px";
      div2.style.border = "1px solid #e7e7e7";
      div2.style.background = "#FFFFFF";
      div.id = "ordid";
      var ul = document.createElement("ul");
      var arr = xhr.responseText.split("|");
      for (var i = 0; i < arr.length; ++i) {
        var li = document.createElement("li");
        li.appendChild(document.createTextNode(arr[i]));
        ul.appendChild(li);
      }
      div2.appendChild(ul);
      div.appendChild(div2);
      if (prodname.nextElementSibling) {
        prodname.parentNode.removeChild(prodname.nextElementSibling);
      }
      prodname.parentNode.appendChild(div);
    } 
  }
  ///////////////////////////////////////////////////////////
  // 数据验证
  
}
</script>
</body>
</html>