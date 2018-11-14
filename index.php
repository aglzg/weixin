<?php
header("Content-type: text/html; charset=utf-8"); 
include "weixin_gongzhonghao.class.php";
$weixin = new weixin_gongzhonghao;
$authorUrl = $weixin->getAuthorUrl();
?>

<!DOCTYPE html>
<html>
<head>
	<title>weixin授权登录</title>
</head>
<body >
<style type="text/css">
	body{text-align: center;padding: 100px 50px;font-size: 30px;}
</style>

<a href="<?php echo $authorUrl;?>" title="">weixin授权登录</a>

</body>
</html>