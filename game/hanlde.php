<?php
	//如果有多个ajax 都请求handle.php,这个 action相当于是一个标志符
	//用ajax请求的时候，判断action传过来的参数，具体要做什么，这样就不用使用多个php文件了
	//用switch来辨别对应的每一个请求
	if(empty($_GET["action"])){
    	die;
    }
//链接数据库
$link = mysqli_connect(SAE_MYSQL_HOST_M, SAE_MYSQL_USER, SAE_MYSQL_PASS, SAE_MYSQL_DB, SAE_MYSQL_PORT);
if(mysqli_connect_errno($link)){
	echo mysqli_connect_error($link);
    die;
}
//echo "数据库连接成功";
//设置编码格式
mysqli_query($link,"SET NAMES UTF8");

	$action = $_GET["action"];
	switch($action){
    	case "sendScore":
            //需要往数据库储存score
            $openid = $_GET["openid"];
            $headimgurl = $_GET["headimgurl"];
            $nickname = $_GET["nickname"];
            $score = $_GET["score"];
            //echo $openid;

           	//判断用户是否曾经玩过该游戏
            $sql = "select * from rank where openid = '{$openid}'";
            $res = mysqli_query($link,$sql);


            //mysqli_num_rows() 返回结果集中行的数目。此命令仅对 SELECT 语句有效。
            //要取得被 INSERT，UPDATE 或者 DELETE 查询所影响到的行的数目，用 mysqli_affected_rows()。


            //$data = mysqli_fetch_array($res,MYSQLI_ASSOC);
            if($res&&mysqli_num_rows($res)){

            	//echo "用户已存在";
                //数据表中已经存在，拿表中的分数与我本次的得分进行比较，如果本次较大，则更新之，反之不更新。
                $row = mysqli_fetch_assoc($res);//获得的是关联数组
                if($score>$row["score"]){
                	$sql = "update rank set score = {$score} where openid = '{$openid}'";
                    $res = mysqli_query($link,$sql);
                    if($res&&mysqli_affected_rows($link)>0){
                    	$data["status"] = 1;
                        $data["msg"] = "update:ok";
                        echo json_encode($data);
                    }else{
                        //更新失败
                    	$data["status"] = 0;
                        $data["msg"] = "update:fail";
                        echo json_encode($data);
                    }

                }else{
                	//本次的分数没有数据库存的分数多，不需要更新
                		$data["status"] = 1;
                        $data["msg"] = "update:no";
	                    echo json_encode($data);
                }

            }else{
            	//echo "用户不存在";

                //游戏的第一次开始，需要将用户的信息插入到数据表中
                //sql语句中，如果有数据是字符串的那么就 '{}',用单引号将其包住，花括号使用的原因的是将其变量隔开
                $sql = "insert into rank (openid,headimgurl,nickname,score) values ('{$openid}','{$headimgurl}','{$nickname}','{$score}')";
				$res = mysqli_query($link,$sql);
                //mysql_insert_id() 返回给定的 connection 中上一步 INSERT 查询中产生的 AUTO_INCREMENT 的 ID 号。
                 //如果没有指定 connection ，则使用上一个打开的连接
                if($res && mysqli_insert_id($link)>0){
                	$data["status"] = 1;//请求的状态码
                    $data["msg"] = "数据插入成功";//请求的信息
                    echo json_encode($data);
                }else{
                	echo '{"status":0,"msg":"插入失败，请检查你的sql语句"}';
                }
            }

            break;
         //
        case "getRank":
            //echo "请求成功";
            //根据分数进行从大到小的排序，并读取前十名
            $sql = "select * from rank order by score desc limit 10";
            $res = mysqli_query($link,$sql);
            if($res&&mysqli_num_rows($res)>0){
            	$arr = [];
                while($row = mysqli_fetch_assoc($res)){
                	$arr[] = $row;
                }
                $data["status"] = 1;
                $data["msg"] = "request:ok";
                $data["result"] = $arr;
                echo json_encode($data);

            }else{
            	 $data["status"] = 0;
                $data[	"msg"] = "request:fail";
                echo json_encode($data);
            }
            break;

    }
?>
