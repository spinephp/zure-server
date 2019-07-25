<?php
    require("controller/controller.php");
    \woo\controller\Controller::run();
?>
#### GET 请求参数:
```JAVASCRIPT
{
	"cmd":"Qiye", // 向数据库中的Qiye表请求数据
	"cond":[{"field":"id","value":"1","operator":"eq"}], // 请求条件数组，相当于 where id=1
	"filter":["id","names"], // 请求的字段数组
	"token":"oj20i5188mvg945mq2h0u2bgv6"  // 防跨站攻击参数
}
```
##### PHP 例子：
```PHP
	$query = new FinalAssember('Qiye',array("id","names"),array(array("field"=>"id","value"=>"1","operator"=>"eq")));
	$result = $query->find();
	return $result; // [{id:1,names:["china","中国"]},{id:2,names:["amrican","美国"]}]
```
##### Angular 例子：
```JAVASCRIPT
<script>
    var app = angular.module('newApp',[]);
    app.controller('headerController',function($scope,$http){
	var token = "oj20i5188mvg945mq2h0u2bgv6";
        $http.get('?cmd=Qiye',{params:{"filter":angular.toJson(["id","names"]),"token":token}
	}).then(function successCallback (rs){
            $scope.qiye = rs.data[0];  // [{id:1,names:["china","中国"]},{id:2,names:["amrican","美国"]}]
        });
    });
 </script>
```
##### jQuery(coffeescript) 例子
```COFFEESCRIPT
	jQuery.ajax
		type: 'get'
		url: '?cmd=Qiye'
		data: {cond:[{'field':'id',value: 1,operator:'eq'}], filter: ["id","names"], token: "oj20i5188mvg945mq2h0u2bgv6"  }
		async: false   #ajax执行完毕后才执行后续指令
		success: (result) =>
			obj = JSON.parse(result)
```
参数加密：<br/>
支持 RSA 加密。<br/>
GET 方式参数加密 ?cmd=xxx&td=...<br/>

其它常用格式：<br/>

检查登录状态：<br/>
```JAVASCRIPT
POST{
	"cmd":"CheckLogin",
	"username":"aaaaaa", // 用户名
	"pwd":"123456", // 密码
	"code":"2530", // 验证码
	"action":"custom_login", // 验证方式
	"token":"user_token" // 防跨站攻击参数
}
```
