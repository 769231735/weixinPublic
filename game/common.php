<?php
	$appid = "wxca7c1665910010c2";
	$appsecret = "4d04e764ee30cbca072d0796907f58d2";



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
