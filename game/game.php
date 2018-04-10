<?php
include_once "common.php";
//1.获得用户授权后传递code值
if(empty($_GET["code"])){
	die;
}
$code = $_GET["code"];
echo $code;
echo "<hr/>";
//2.获取access_token和openId
$getToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";

$res = httpGet($getToken);
$arr = json_decode($res,true);
$access_token = $arr["access_token"];
$openid = $arr["openid"];
echo $access_token;
echo "<hr/>";

//用授权得到的access_token和openid获得用户信息
$userInfo = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
$res = httpGet($userInfo);
$arr = json_decode($res,true);
$nickname = $arr["nickname"];
$headimgurl = $arr["headimgurl"];
echo $headimgurl;




?>
<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxca7c1665910010c2", "4d04e764ee30cbca072d0796907f58d2");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Document</title>
    <style media="screen">
        .btn , .rankBtn{
            display: block;
            width: 110px;
            height: 35px;
            text-align: center;
            line-height: 35px;
            text-decoration: none;
            background: skyblue;
            margin: 40px auto;
        }
    </style>
</head>
<body>
    <!--  点击按钮开始游戏，随机出来一个得分，作为本次得分-->
    <a class="btn" href="###" >开始游戏</a>
    <a class = "rankBtn">排行榜</a>
    <table>
    	<tr>
            <th>排名</th>
            <th>头像</th>
            <th>昵称</th>
            <th>得分</th>
        </tr>
    </table>
    <h2 class="top"></h2>
</body>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript">
	var openid = "<?php echo $openid?>";
    var nickname = "<?php echo $nickname?>";
    var headimgurl = "<?php echo $headimgurl?>";
    //$("<div/>").text("昵称："+nickname).appendTo($("body"));
    //$("<img/>").attr('src',headimgurl).appendTo($("body"));

</script>
<script type="text/javascript" src="game.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      "onMenuShareTimeline"
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
      wx.onMenuShareTimeline({
        title: '一辈子的愿望，数钱数到手软！！!', // 分享标题
        link: 'http://lanoutech.applinzi.com/game/index.html', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=3278114738,656696973&fm=27&gp=0.jpg', // 分享图标
        success: function () {
        // 用户确认分享后执行的回调函数
            alert("分享成功");
    	},
        cancel: function () {
            // 用户取消分享后执行的回调函数
            alert("分享取消");
           }
        });
  });
</script>

</html>
