<?php
    include "getToken.php";
//获取access_token值
    $token = getToken($link,$appid,$appsecret);
	echo $token;
//获取用户微信服务器ip的地址
    $ipListApi = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token={$token}";
    $res = httpGet($ipListApi);
    print_r($res);
	$obj = json_decode($res);
	echo "<hr/>";
	var_dump($obj);
	//php对象调用属性和方法使用 -> 调用
    $ipList = $obj->ip_list;
	echo "<pre>";
    var_dump($ipList);

	echo "<hr/>";
 //获取已关注的公众号的列表
    $getUserList =  "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$token}";
    $res  = httpGet($getUserList);
    $arr = json_decode($res,true);
	var_dump($arr);

	echo "<hr/>";

//	获取已关注公众号的用户信息
	$openid = "oaLZdwjyIj1b0Qvl5Txu2ujtF9I4";
	$getUserInfo  = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
	$res  = httpGet($getUserInfo);
	$arr = json_decode($res,true);
	var_dump($arr);

	echo $arr["nickname"];
    $imgUrl = $arr["headimgurl"];
	echo "<img src='{$imgUrl}'>";
    echo "<hr/>";
    // 长链接转为短链接
     $long2short = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
     $arr['action'] = "long2short";
     $arr["long_url"] = "https://baike.baidu.com/item/%E9%AB%98%E7%AD%89%E5%AD%A6%E6%A0%A1%E5%AD%A6%E7%A7%91%E5%88%9B%E6%96%B0%E5%BC%95%E6%99%BA%E8%AE%A1%E5%88%92/10958837?fr=aladdin&fromid=541410&fromtitle=111";
     $data = json_encode($arr);
	 //post请求里的$data参数为json字符串，对象或者数组要进行转化
	 $res = httpPost($data,$long2short);
	 echo 1111;
     var_dump($res);
	echo "<hr/>";

//自定义菜单
    $data = <<<EOF
    {
         "button":[
    	     {
    	        "type":"click",
    	        "name":"点歌大王",
    	         "key":"V1001_TODAY_MUSIC"
    	     },
             {
             	"name":"菜单",
            		"sub_button":[
            			{
                			"type":"view",
                			"name":"搜索",
                			"url":"http://www.soso.com/"
            			},

                 	{
    			        "type":"click",
    			        "name":"数钱游戏",
    			        "key":"V1001_GOOD",
                        "url":"http://1.lanoutech.applinzi.com/countMoney/index.html"
               		}
           		]
     		}
     	],
    	"matchrule":{
      		"tag_id":"2",
    	    "sex":"1",
    	    "country":"中国",
    	    "province":"广东",
    	   "city":"广州",
    	   "client_platform_type":"2",
    	   "language":"zh_CN"
        }
    }
EOF;
$createMenu = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
$res = httpPost($data,$createMenu);
echo $res;










    //php里面请求post接口的函数
	function httpPost($data,$url){
	         $ch = curl_init();
	         curl_setopt($ch, CURLOPT_URL, $url);
	         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	         curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	         $tmpInfo = curl_exec($ch);
	         if (curl_errno($ch)) {
	          return curl_error($ch);
	       	}
	       	curl_close($ch);
	        return $tmpInfo;
	    }




?>
