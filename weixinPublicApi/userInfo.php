<?php
$appid = "wxca7c1665910010c2";
$appsecret = "4d04e764ee30cbca072d0796907f58d2";
// 接受用户同意时返回的code值,code只能使用一次
$code = $_GET["code"];
//echo $code;
//第二步：通过code换取网页授权access_token
$getAccessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";

$res = httpGet($getAccessToken);
//echo $res;
$arr = json_decode($res,true);

$token = $arr["access_token"];
$openid = $arr["openid"];

$getUserInfo = "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN";

$res = httpGet($getUserInfo);

echo $res;
$arr = json_decode($res,true);
$nickname = $arr["nickname"];
$headimgurl = $arr["headimgurl"];





//get请求方式
	//1.初始化curl
	//2.设置curl
	//3.执行curl
	//4.关闭curl
	function httpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
	    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
	   // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	   // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
	    curl_close($curl);
	    return $res;
	  }


 ?>
 <!DOCTYPE html>
 <html>
 	<head>
 		<meta charset="utf-8">
 		<title></title>
 	</head>
 	<body>
		<h1><?php echo $nickname; ?></h1>
		<img src="<?php echo $headimgurl; ?>" alt="">
 	</body>
	<script type="text/javascript">
		var nickname = "<?php echo $nickname; ?>";
		var headimgurl = "<?php echo $headimgurl; ?>";
		console.log(nickname);

	</script>
 </html>
