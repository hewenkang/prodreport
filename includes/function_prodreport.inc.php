<?php 
// 检查用户是否具有权限
function checkUserAuthority ($autority, $arr_authority)
{
	if (!in_array($autority, $arr_authority)) {
    $errmsg = '<p>您没有访问此页面的权限，请联系管理员</p>';
    include '../../error.html.php';
    exit();
  }
}
// 因遇到错误退出
function exit_error ($errmsg) {
	include '../error.html.php';
  exit();
}

// 获得用户角色，用于登录验证
function getUserRoles($pdo, $name)
{
	$sql = 'SELECT rolename FROM roles 
	INNER JOIN userrole ON userrole.roleid=roles.roleid
	INNER JOIN users ON users.userid=userrole.userid
	WHERE users.username=:name';
	$value["name"] = $name;
	$s = doBindQuery($pdo, $sql, $value);
	$role = $s->fetch();
	return $role[0];
}

// 获取用户的模块，每个用户一行
function getUserModules($pdo, $name)
{
	$sql = "SELECT a.modid, a.modname FROM modules AS a
	INNER JOIN rolemodule ON rolemodule.modid=a.modid
	INNER JOIN userrole ON userrole.roleid=rolemodule.roleid
	INNER JOIN users ON users.userid=userrole.userid
	WHERE a.parentid=0 and users.username='$name'";
	$msg = "获取用户模块时发生错误";
	$value["name"] = $name;
	foreach ($value as $k => $v) {
		$key[] = $k;
	}
	$s = doBindQuery($pdo, $sql, $value);

	foreach ($s as $key => $value) {
		$sql = "SELECT d.modname FROM users AS a
						INNER JOIN userrole AS b ON a.userid = b.userid
						INNER JOIN rolemodule AS c ON b.roleid = c.roleid
						INNER JOIN modules AS d ON d.modid = c.modid AND d.parentid=".$value["modid"].
						" AND a.username='".$name.
						"' order by d.modid";
		$msg = "获取用户子模块时发生错误";
		$result = doQuery($pdo, $sql);
		
		$arr[0]=$value["modname"];
		if ($result->rowCount() !== 0) {
			foreach ($result as $key => $value) {
				$arr[] = $value["modname"];
			}
		}
		$row[] = $arr;
		unset($arr);
	}
	return $row;
}

function getAllModules($pdo)
{
	$sql = "SELECT * FROM modules";
	$msg = "获取所有模块时发生错误";
	$result = doQuery($pdo, $sql);
	$arr = array();
	foreach ($result as $row) {
		$arr[$row['modname']]=$row['moddes'];
	}
	return $arr;
}

function generate_page_links($module ,$cur_page, $num_pages) {
	// 如果当前页不是第一页，生成“上一页” 链接
	$page_links = "";
	if ($cur_page > 1) {
		$page_links .= '<a href="?' . $module . '&page=' . ($cur_page-1) . '">上页 </a>';
	}
	else {
		$page_links .= '上页';
	}
	if ($cur_page > 10) {
		$page_links .= '<a href="?' . $module . '&page=' . ($cur_page - ($cur_page % 10 === 0? 10 : $cur_page % 10)) . '">... </a>';
	}

	if ($cur_page % 10 === 0) {
		$start = $cur_page - 10 +1;
	}
	else {
		$start=$cur_page - $cur_page % 10 + 1;
	}

	// 循环创造页码链接
	for ($i=0; $start+$i <= $num_pages; $i++) { 
		if ($cur_page == $start+$i) {
			$page_links .= '' . ($start+$i);
		}
		else {
			$page_links .= '<a href="?' . $module . '&page=' . ($start+$i) . '"> ' . ($start+$i) . ' </a>';
		}
		if ($i >= 9) {
			$page_links .= '<a href="?' . $module . '&page=' . ($cur_page - ($cur_page % 10 === 0? 10 : $cur_page % 10)+11) . '">... </a>';
			break;
		}
	}
	// 如果当前页不是第一页，生成“下一页” 链接
	if ($cur_page < $num_pages) {
		$page_links .= '<a href="?' . $module . '&page=' . ($cur_page+1) . '"> 下页</a>';
	}
	else {
		$page_links .= '下页';
	}
	return $page_links;
}
?>