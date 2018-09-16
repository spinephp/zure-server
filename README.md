<?php
    require("controller/controller.php");
    \woo\controller\Controller::run();
?>
#### GET 请求参数:
```JAVASCRIPT
{
	"cmd":"Qiye", // 向数据库中的Qiye表请求数据
	"cond":[{"field":"id","value":"1","operator":"eq"}], // 请求条件数组，相当于 where id=1
	"filter":["id","names","domain","qq","tel","fax"], // 请求的字段数组
	"token":"oj20i5188mvg945mq2h0u2bgv6"  // 防跨站攻击参数
}
```
Angular 例子：<br/>
```JAVASCRIPT
<script>
    var app = angular.module('newApp',[]);
    app.controller('headerController',function($scope,$http){
		var token = "oj20i5188mvg945mq2h0u2bgv6";
        $http.get('?cmd=Qiye',
		{params:{"filter":angular.toJson(["id","names","addresses","tel","icp"]),"token":token}
		}).then(function successCallback (rs){
            $scope.qiye = rs.data[0];
        });
    });
 </script>
```
   
参数加密：<br/>
支持 RSA 加密。<br/>
GET 方式参数加密 ?cmd=xxx&td=...<br/>

其它常用格式：<br/>

检查登录状态：<br/>
```JAVASCRIPT
POST{<br/>
"cmd":"CheckLogin",<br/>
"username":"aaaaaa", // 用户名<br/>
"pwd":"123456", // 密码<br/>
"code":"2530", // 验证码<br>
"action":"custom_login", // 验证方式<br/>
"token":"user_token" // 防跨站攻击参数</br>
}
```
