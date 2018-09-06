<?php
    require("controller/controller.php");
    \woo\controller\Controller::run();
?>
GET 请求参数:
{
    "cmd":"Qiye", // 向数据库中的Qiye表请求数据
    "cond":[{"field":"id","value":"1","operator":"eq"}], // 请求条件数组，相当于 where id=1
    "filter"["id","names","domain","qq","tel","fax"], // 请求的字段数组
    "token":"oj20i5188mvg945mq2h0u2bgv6"  // 防跨站攻击参数
}
