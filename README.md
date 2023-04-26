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
#### PUT 请求参数:
```JAVASCRIPT
{
	"cmd":"Qiye/2", // 向数据库中的Qiye表更新数据, 2 - 要更新的记录 id
	"item":{
		"qiye": {"name":"张三","password":"123456"},
		"action": "qiye_update", 
		"token":"oj20i5188mvg945mq2h0u2bgv6"  // 防跨站攻击参数
	}
}
```
##### SWIFT 例子：
```SWIFT
            struct PostLevel: Codable, Parsable  {
                var mame: String?
                var password: String?
            }
            struct PostLevelReturn: Codable, Parsable {
                var id: Int
                var level: [
                    PostLevel
                ]
            }
            var levelreturn = PostLevelReturn(id: -1, level: [])
            let params: [String: Any] = [
                "level":[
                    "name": value.name,
                    "password": value.password,
                ],
                "action": "level_update",
                "token": token
            ]
            Net.put(data: levelreturn, url: "Level/\(user!.id)", body: params, completionHandler: {result in
                switch result {
                   case .success(let data):
                    levelreturn = data
                   case .failure(let error):
                     print("get level failure: \(error)")
                   }
            })
```
##### Angular 例子：
```JAVASCRIPT
<script>
    var app = angular.module('newApp',[]);
    app.controller('headerController',function($scope,$http){
	var token = "oj20i5188mvg945mq2h0u2bgv6";
        $http.put('?cmd=Qiye/2',{params:{
		"qiye": {"name":"张三","password":"123456"},
		"action": "qiye_update", 
		"token":"oj20i5188mvg945mq2h0u2bgv6"
	}
	}).then(function successCallback (rs){
            $scope.qiye = rs.data[0];  // {id:2,qiye:{"name":"张三","password":"123456"}}
        });
    });
 </script>
```
##### jQuery(coffeescript) 例子
```COFFEESCRIPT
	jQuery.ajax
		type: 'put'
		url: '?cmd=Qiye/2'
		data: {
		"qiye": {"name":"张三","password":"123456"},
		"action": "qiye_update", 
		"token":"oj20i5188mvg945mq2h0u2bgv6"
	}
		async: false   #ajax执行完毕后才执行后续指令
		success: (result) =>
			obj = JSON.parse(result)
```
参数加密：<br/>
支持 RSA 加密。<br/>
GET 方式参数加密 ?cmd=xxx&td=...<br/>

其它常用格式：<br/>

检查登录状态：CheckLogin<br/>
```JAVASCRIPT
GET{
	"cmd":"CheckLogin",
	"username":"aaaaaa", // 用户名
	"pwd":"123456", // 密码
	"code":"2530", // 验证码
	"action":"custom_login", // 验证方式
	"token":"user_token" // 防跨站攻击参数
}
```
##### Angular2 例子：
```TYPESCRIPT
export class Userlogin {
    constructor(
        public username: string,
        public pwd: string,
        public code: string,
        public action: string,
        public token: string	// sessionid
    ) {}
}
...
loginModel = new Userlogin('张三', '123456', '1234', 'custom_login', sessionid);
param = JSON.stringify(loginModel);
this.requestService.get('http://www.xxx.com/index.php?cmd=CheckLogin', param).then(rs => {
      return rs; // { active: "Y", email: "xyz@gmail.com", id: "3", name: "张三", picture: "u00000003.png", state: 1}
});

```

：Register<br/>
```JAVASCRIPT
POST{
	"cmd":"Custom",
	{
		"custom":{
			"type":"P"
		},
		"person":{
			"username":"aaaaaa", // 用户名,
			"pwd":"Aa112112", // 密码
			"email":"gstools@qq.com",
			"times":"0"
		},
		"code":"4529", // 验证码
		"action":"custom_create", // 验证方式
		"language":0,
		"token":"qborm7obvjgngns23r2r9iautl" // 防跨站攻击参数
	}
}
```
##### Angular2 例子：
```TYPESCRIPT
const params = {
		"custom":{
			"type":"P"
		},
		"person":{
			"username":"aaaaaa",
			"pwd":"Aa112112",
			"email":"gstools@qq.com",
			"times":"0"
		},
		"code":"4529",
		"action":"custom_create",
		"language":0,
		"token":"qborm7obvjgngns23r2r9iautl"
	}
...
this.requestService.get('/api/index.php?cmd=Custom', JSON.stringify(params)).then(rs => {
      const user = rs; // { id: 132, custom:[{id: 132, type: 'P', userid: 192}], person: [{id: 192, username: "xxxxxx", email: "abc@xx.com",times: "0"}], email: "账号激活邮件已发送到你的邮箱中。激活邮件48小时内有效。请尽快登录您的邮箱点击激活链接完成账号激活。", register: "账号注册成功！"}
});

```

退出用户登录：Logout<br/>
```JAVASCRIPT
POST{
	"cmd":"Logout",
	"user":"xxx@yyy.com", // 用户名
	"action":"custom_logout", // 验证方式
	"token":"user_token" // 防跨站攻击参数
}
```
##### Angular2 例子：
```TYPESCRIPT
const param = {
    user: 'userone',
    action: 'custom_logout',
    token: 'oj20i5188mvg945mq2h0u2bgv6'
};
...
return this.requestService.post('/woo/index.php?cmd=Logout', JSON.stringify(param)).then(rs => {
	return rs; // {id: 0, username: "userone"}
});

```

：ResetPassword<br/>
```JAVASCRIPT
POST{
	"cmd":"ResetPassword",
	{
		username: "aaaaaa", // 用户名,
		email: 'xxxxx@qq.com',
		code: '1234', // 验证码
		type: 'ResetPassword',
		action: 'custom_resetPassword', // 验证方式
		language: 1, // 
		token: "qborm7obvjgngns23r2r9iautl" // 防跨站攻击参数
	}
}
```
##### Angular2 例子：
```TYPESCRIPT
const params = {
	username: "aaaaaa", // 用户名,
	email: 'xxxxx@qq.com',
	code: '1234', // 验证码
	type: 'ResetPassword',
	action: 'custom_resetPassword', // 验证方式
	language: 1, // 
	token: "qborm7obvjgngns23r2r9iautl" // 防跨站攻击参数
};
...
this.requestService.get('/api/index.php?cmd=ResetPassword', JSON.stringify(params)).then(rs => {
      const user = rs; // { id: 192}
});

```
