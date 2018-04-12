<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxca7c1665910010c2", "4d04e764ee30cbca072d0796907f58d2");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name = "viewport" content = "width=device-width"/>
  <title></title>
</head>
<body>
    <button id="chooseImg">
    	上传图片按钮
    </button>

</body>
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
     "onMenuShareTimeline",
      "onMenuShareAppMessage",
      "chooseImage",
      "uploadImage",//只是上传到微信服务器，并且只有三天时限
      "downloadImage",//下载之后，返回本地图片地址，可以作为img的src使用
    ]
  });
    wx.ready(function () {
    // 在这里调用 API
            wx.onMenuShareTimeline({
                title: '女王节驾到,速来抢购！！！', // 分享标题
                link: 'http://lanoutech.applinzi.com/send.html', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://img.zcool.cn/community/0142135541fe180000019ae9b8cf86.jpg@1280w_1l_2o_100sh.png', // 分享图标
                success: function () {
                // 用户确认分享后执行的回调函数
                alert("分享成功");
                }
            });
        	wx.onMenuShareAppMessage({
                title: '这是测试的分享朋友标题', // 分享标题
                desc: '这是对分享盆友的描述', // 分享描述
                link: 'http://lanoutech.applinzi.com/send.html', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://img.zcool.cn/community/0142135541fe180000019ae9b8cf86.jpg@1280w_1l_2o_100sh.png', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                // 用户确认分享后执行的回调函数
                    alert("分享给朋友成功");
                },
                cancel: function () {
                // 用户取消分享后执行的回调函数
                    alert("已取消分享");
                }
            });
        //选择图片
        var chooseImg = document.getElementById("chooseImg");
        chooseImg.onclick = function(){
            console.log(1111);
        	 wx.chooseImage({
                count: 3, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function (res) {
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    console.log(localIds);
                    for(var i in localIds){
                    	localIs:localIds[i]
                    }
                    wx.uploadImage({
                        localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
                        isShowProgressTips: 1, // 默认为1，显示进度提示
                        success: function (res) {
                            alert("上传到微信服务器成功");
                        	var serverId = res.serverId; // 返回图片的服务器端ID
                            //通过serverId将图片从微信服务器下载到我们自己的本地服务器上
                                wx.downloadImage({
                                    serverId: serverId, // 需要下载的图片的服务器端ID，由uploadImage接口获得
                                    isShowProgressTips: 1, // 默认为1，显示进度提示
                                    success: function (res) {
                                    	var localId = res.localId; // 返回图片下载后的本地ID,这个Id可以作为图地址使用
                                        var imgObj = document.createElement("img");
                                        imgObj.src = localId;
                                        document.getElementsByTagName("body")[0].appendChild(imgObj);
                                    }
                                });

                    	}
					});
                }
            });
        };
        //
        ////

    });

</script>
</html>
