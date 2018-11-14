<?php

/**
* -----公众号授权 获取微信用户信息----- 
*条件:	1. 公众号要验证
*		2. 要关注公众号才能 获取微信用户信息
* 	
* 设置 OAuth2.0网页授权
* -> 授权回调页面域名: 例：www.szluh.com
* 
* 1 第一步：用户同意授权，获取code
* 2 第二步：通过code换取网页授权access_token
* 3 第三步：刷新access_token（如果需要）
* 4 第四步：拉取用户信息(需scope为 snsapi_userinfo)
* 5 附：检验授权凭证（access_token）是否有效
*
* @author [AG] <[<aglzg2016@163.com>]>: 
* @code time [2018年11月7日15:24:41]
*/


class weixin_gongzhonghao{
	var $appid; //公众号的唯一标识
	var $secret; //公众号的appsecret
	var $redirect_uri; //授权回调地址

	function __construct(){
		$this->appid = "wx8e07012f8022ef7e";
		$this->secret = "22384562d1558a287ddfe3b7edb34f5e";
		$this->redirect_uri = "http://www.szluh.com/apiweixin/getUserInfo.php";
	}
	private function getJson($url){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return json_decode($output, true);
	}
	// 跳转微信授权URL
	public function getAuthorUrl(){
		// 关于网页授权的两种 scope 的区别说明
		// 1、以 snsapi_base 为 scope 发起的网页授权，是用来获取进入页面的用户的 openid 的，并且是静默授权并自动跳转到回调页的。用户感知的就是直接进入了回调页（往往是业务页面）
		// 2、以 snsapi_userinfo 为 scope 发起的网页授权，是用来获取用户的基本信息的。但这种授权需要用户手动同意，并且由于用户同意过，所以无须关注，就可在授权后获取该用户的基本信息。
		// 3、用户管理类接口中的“获取用户基本信息接口”，是在用户和公众号产生消息交互或关注后事件推送后，才能根据用户 OpenID 来获取用户基本信息。这个接口，包括其他微信接口，都是需要该用户（即 openid ）关注了公众号后，才能调用成功的。

		$redirect_uri = urlencode ($url);
		$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=$this->redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
		return $url;
	}
	// 获取 access_token
	private function getToken(){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->secret";
		$token = $this->getJson($url);
		return $token;
	}
	// 获取 weixinUser openid
	private function getOauth2Url($code){
		$oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->secret&code=$code&grant_type=authorization_code";
		$oauth2 = $this->getJson($oauth2Url);
		return $oauth2;
	}
	// 获取 weixinUserInfo
	private function getWeiXinUserInfo($access_token,$openid){
		$get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
		$userinfo = $this->getJson($get_user_info_url);
		return $userinfo;
	}
	// 获取 weixin粉丝列表
	private function getWeiXinUserList($access_token){
		$get_user_list_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";
		$userlist = $this->getJson($get_user_list_url);
		return $userlist;
	}

	// 获取 weixinUserInfo
	public function getUserInfo(){		

		$code = $_GET["code"];	

		//第一步:取全局access_token
		$token = $this->getToken();
		$access_token = $token["access_token"];
		 
		//第二步:取得用户openid
		$oauth2 = $this->getOauth2Url($code);
		$openid = $oauth2['openid'];
		  
		//第三步:根据全局access_token和openid查询用户信息  
		$userinfo = $this->getWeiXinUserInfo($access_token,$openid);

		return $userinfo;
	}

	// 获取 weixin粉丝列表
	public function getUserList(){

		return $this->defUserList();//测试用户列表数据

		//取全局access_token
		$token = $this->getToken();
		$access_token = $token["access_token"];

		$userlist = $this->getWeiXinUserList($access_token);

		return $userlist;
	}

	// 测试用户信息数据
	public function defUserInfo(){
		$userinfo['openid'] 			= "o7jsm5_ZYXu8Nb4WNVEkr7qtRGcA";
		$userinfo['nickname'] 			= "AG";
		$userinfo['sex'] 				= "1";
		$userinfo['language'] 			= "zh_CN";
		$userinfo['city'] 				= "";
		$userinfo['province'] 			= "";
		$userinfo['country'] 			= "科威特";
		$userinfo['headimgurl'] 		= "http://thirdwx.qlogo.cn/mmopen/PiajxSqBRaEJuBVsD4kWJnXtFQDuBey24tLWroG54gpfGhbC4GFg4E2iacpjHibqm8SfZlbpudKEL1aGdAtUAvTCQ/132";
		$userinfo['subscribe_time'] 	= "1541558356";
		$userinfo['remark'] 			= "";
		$userinfo['groupid'] 			= "0";
		$userinfo['tagid_list'] 		= Array();
		$userinfo['subscribe_scene']	= "ADD_SCENE_QR_CODE";
		$userinfo['qr_scene']			= "0";
		$userinfo['qr_scene_str']		= "";
		return $userinfo;
	}
	// 测试用户列表数据
	public function defUserList(){
		$userlist['total'] = 1;
		$userlist['count'] = 1;
		$userlist['data']['openid'] = array("o7jsm5_ZYXu8Nb4WNVEkr7qtRGcA");
		$userlist['next_openid'] = "o7jsm5_ZYXu8Nb4WNVEkr7qtRGcA";
		return $userlist;
	}
}

?>