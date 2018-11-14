<?php
header("Content-type: text/html; charset=utf-8"); 
include "weixin_gongzhonghao.class.php";
session_start();
$weixin = new weixin_gongzhonghao;
$userinfo = $weixin->getUserInfo();

// $userinfo = $userinfo['errcode'] ? $weixin->defUserInfo() : $userinfo ;
?>
<!DOCTYPE html>
<html>
<head>
	<title>weixin</title>
</head>
<body style="font-size: 30px;">	
	<table>
		<tr><td>openid</td><td><?php echo $userinfo['openid'];?></td></tr>		
		<tr><td>nickname</td><td><?php echo $userinfo['nickname'];?></td></tr>		
		<tr><td>sex</td><td><?php echo $userinfo['sex'];?></td></tr>		
		<tr><td>city</td><td><?php echo $userinfo['city'];?></td></tr>		
		<tr><td>province</td><td><?php echo $userinfo['province'];?></td></tr>		
		<tr><td>country</td><td><?php echo $userinfo['country'];?></td></tr>		
		<tr><td>headimgurl</td><td><img src="<?php echo $userinfo["headimgurl"];?>"></td></tr>		
		<tr><td>subscribe_time</td><td><?php echo $userinfo['subscribe_time'];?></td></tr>		
		<tr><td>remark</td><td><?php echo $userinfo['remark'];?></td></tr>		
		<tr><td>groupid</td><td><?php echo $userinfo['groupid'];?></td></tr>		
		<tr><td>tagid_list</td><td><?php echo $userinfo['tagid_list'];?></td></tr>		
		<tr><td>subscribe_scene</td><td><?php echo $userinfo['subscribe_scene'];?></td></tr>		
		<tr><td>qr_scene</td><td><?php echo $userinfo['qr_scene'];?></td></tr>			
		<tr><td>qr_scene_str</td><td><?php echo $userinfo['qr_scene_str'];?></td></tr>		
	</table>
</body>
</html>