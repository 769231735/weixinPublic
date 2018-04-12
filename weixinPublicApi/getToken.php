<?php
	//封装的获取access_token值的函数
	  function http($appid,$appsecret){
	  	$getTokenUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
	  	$str = httpGet($getTokenUrl);
	  	$arr = json_decode($str,true);//默认第二个参数为false返回的为对象，设置为true返回的是数组
		$token = $arr["access_token"];
		return $token;
	  }
//appid
	$appid = "wxca7c1665910010c2";
	$appsecret = "4d04e764ee30cbca072d0796907f58d2";
	$link = mysqli_connect(SAE_MYSQL_HOST_M,SAE_MYSQL_USER,SAE_MYSQL_PASS,SAE_MYSQL_DB,SAE_MYSQL_PORT);
	if(mysqli_connect_errno($link)){
		echo mysqli_connect_error($link);
	}else{
		echo "<br/>数据库链接成功";
	}
//getToken
	function getToken($link,$appid,$appsecret){
		//返回一个可以使用的access_token(打开公众号开发的钥匙)
		$sql = "SELECT * FROM token";
		$res = mysqli_query($link,$sql);
        //		var_dump $res;
        //查询有结果，并且获得的数据长度不为0时表示存过
		if($res && mysqli_num_rows($res)){
			echo "<br/>已存过";
			$sql = "SELECT * FROM token WHERE id = 1";
			$res = mysqli_query($link,$sql);
			$data = mysqli_fetch_assoc($res);
			$time2 = time();
			$time1 = $data["time"];
			// echo $res;
			//var_dump ($data);
			echo $time1;
			if ($time2-(int)$time1>7100) {
				echo "<br/>token过期";
				$token = http($appid, $appsecret);
				//echo "<br/>".$token;
				$sql = "UPDATE token SET token='{$token}', time = '{$time2}' where id = 1";
				$res = mysqli_query($link,$sql);
				if($res && mysqli_affected_rows($link)>0){
                	echo "更新成功";
					return $token;
                }else{
                    echo "更新失败";
                }
			}else {
				echo "token未过期";
				$token = $data["token"];
				return $token;
			}
		}else{
			echo "<br/>没存";
			$token = http($appid, $appsecret);
			echo $token;
			$time = time();
			$sql = "INSERT INTO token (token,time) VALUES ('{$token}','{$time}')";
			$res = mysqli_query($link,$sql);
			if($res&&mysqli_insert_id($link)>0){
				echo "<br/>存储token成功";
			}else{
				echo "<br/>存储token失败";
			}
		}
	}


 //////////////////////////////
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
