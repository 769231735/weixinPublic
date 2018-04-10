function rand(min,max){
    return parseInt(Math.random()*(max-min+1)+min);
}
var btn = $(".btn");
btn.click(function(){

    var score = rand(100,10000);
    alert(score);
   	//console.log(nickname,openid,headimgurl);
    // 拿到分数，发送ajax请求，向后台传输（score,openId,headimgUrl,nickName）
    $.ajax({
    	type:"get",
        url:"handle.php?action=sendScore",
        data:{
        	openid:openid,
            nickname:nickname,
            headimgurl:headimgurl,
            score:score
        },
        success:function(res){
        	console.log(res);
          	//var top = document.getElementsByTagName("h2")[0];
            //$(".top").text("最高分："+res);
            //console.log(top);
            var obj = $.parseJSON(res);
            if(obj.status){
                console.log("成功了");
            	//window.location.href = "index.html";
            }
        }
    });

})

//排行榜

var rankBtn = $(".rankBtn");
rankBtn.click(function(){
         $.ajax({
            type:"get",
            url:"handle.php?action=getRank",
            dataType:"json",
            success:function(res){

                console.log(res);
                var data = res;
                if(data.status){
                    var result = data.result;
                    var num = 0;
                    for(var i in result){
                        num++;
                        var els = $("<tr>"+
                "<td>"+num+"</td>"+
                "<td><img src='"+result[i].headimgurl+"'/></td>"+
                "<td>"+result[i].nickname+"</td>"+
                "<td>"+result[i].score+"</td>"+
            "</tr>").appendTo($("table"));



                    }
                }
            }
        });    
});
