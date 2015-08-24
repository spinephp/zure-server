<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8>
  <title>App</title>
  <link rel="stylesheet" href="css/orderdetail.css" type="text/css" charset="utf-8">
  <link rel="stylesheet" type="text/css" href="themes/redmond/jquery-ui.min.css" />
  <script src="scripts/orderdetail.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" charset="utf-8">
    var jQuery  = require("jqueryify");
    var exports = this;
    require("lib/jquery-ui.min");
    jQuery(function(){
      var App = require("index");
      exports.app = new App({el: $("body")});
    });
  </script>
  
</head>
<body></body>
</html>
