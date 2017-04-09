<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8>
  <title>App</title>
  <link rel="stylesheet" type="text/css" href="themes/redmond/jquery-ui.min.css" />
  <link rel="stylesheet" href="css/office.css" type="text/css" charset="utf-8">
  <script src="scripts/office.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" charset="utf-8">
    //var jQuery  = require("jqueryify");
    require("lib/jquery.ztree.core-3.5.min");
    require("lib/jquery-ui.min");
    var exports = this;
    jQuery(function(){
      var App = require("index");
      exports.app = new App({el: $("#article")});
    });
  </script>
  
</head>
<body>
  <header id="header"><h1>连云港云瑞耐火材料有限公司网站管理系统</h1><h2></h2></header>
  <article id="article"></article>
</body>
</html>
