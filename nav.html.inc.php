<header role="banner">
	<nav role="navigation" class="navbar navbar-static-top navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<?php 
						foreach($_SESSION['all_modules'] as $key=>$value){
							if(in_array($key, $_SESSION['authority']))
							{
								echo "<span class='icon-bar'></span>";
							}
						}
					 ?>
				</button>
				<a href="#" class="navbar-brand">晋西车轴生产月报系统</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">

          <?php foreach ($_SESSION['modules'] as $value):?>
            <?php if (count($value)>1): ?>
              <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" 
              aria-haspopup="true" aria-expanded="false">
                <?php echo $_SESSION['all_modules'][$value[0]]; ?>
              <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
              <?php 
                $count = count($value);
                if ($count>1) {
                  for($i=1; $i<$count; $i++) {
                    echo "<li><a href=\"?$value[$i]\">".$_SESSION['all_modules'][$value[$i]]."</a></li>";
                  }
                } 
              ?>
              </ul>               
              </li>
            <?php else: ?>
              <?php echo "<li><a href=\"?$value[0]\">".$_SESSION['all_modules'][$value[0]]."</a></li>"; ?>
            <?php endif ?>  
          <?php endforeach;?>
            
          <?php if (count($_SESSION["system"])>1): ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" 
              aria-haspopup="true" aria-expanded="false">
                切换系统
              <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <?php 
                  foreach ($_SESSION['system'] as $value) {
                    echo "<li><a href='?chgSystem&sysid=".$value["sysid"]."'>".
                    $value['sysname']."</a></li>";
                  } 
                ?>
              </ul>               
            </li>
          <?php endif ?>
				</ul>
			</div>	
		</div>
	</nav>
</header>
