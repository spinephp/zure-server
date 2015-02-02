<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8>
  <title>App</title>
  <link rel="stylesheet" href="css/orderinfo.css" type="text/css" charset="utf-8">
  <script src="scripts/orderinfo.js" type="text/javascript" charset="utf-8"></script>
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
