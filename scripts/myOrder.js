//
// Replace prototype
//
// String.prototype.myformat = function () {
    // var args = arguments;
    // return this.replace(/\{(\d)\}/g, function (a, b) {
        // return typeof args[b] != 'undefined' ? args[b] : a;
    // });
// }


var myOrderDialog = function () {
    var __refactor__ = true;//是否需要重新构建 该对话框
    return {
        open: function (options) {
            var dlgAddOrder = $("#myOrderDialog");
            if (__refactor__) {
                dlgAddOrder.remove();
            }

            //if ($("#registDialog").size() > 0) {
            //    $("#registDialog").dialog("open");
            //    return;
            //}

            var html = "",
                kind = 0,
                sum = 0;
            html += "<div id='myOrderDialog'>";
            html += "<h4>最新加入的商品</h4>";
            html += "<table>";
            for (var i = 0; i < options.length; i++) {
                var rec = options[i].aRecordEx();
                html += "<tr><td><a href='? cmd=ShowProducts&prosid=" + options[i].proid + "'><img src='admin/uploadimg/" + rec.image + "' width=48 height=32 />";
                html += rec.longname + "<br />" + rec.size + "</a></td>";
                html += "<td><span>¥" + rec.price + "</span>*" + options[i].number + "<br /><a href='#' product-data='" + options[i].proid + "'>删除</a></td></tr>";
                kind += 1;
                sum += rec.price * options[i].number;
            };
            html += "</table>";
            html += "<p>订单包含 " + kind + " 种产品，合计：<span>¥" + sum.toFixed(2) + "</span></p><br />";
            html += "<p><button>订单结算</button></p>";
            html += "</div>";

            $(html).appendTo("body");


            $("#myOrderDialog button").button().click(function () {
                location.href = "? cmd=ShowOrder&token="+sessionStorage.token;
            });

            $("#myOrderDialog").delegate("a", "click", function () {
                var proid = $(this).attr("product-data");
                var item = AOrder.findByAttribute("proid", proid);
                item.destroy();
            });

            var offset = $("#weizhi ul li:last-child").position(),
                scrollY = $("body").scrollTop();
            $("#myOrderDialog").dialog({
                autoOpen: false,
                closeOnEscape: true,
                width: '400px',
                position: [offset.left-275, offset.top+27-scrollY],    // 赋值弹出坐标位置
                modal: false,
                //title: "添加订单",
                buttons: {
                    //"订单结算": function () {
                    //    __refactor__ = true;
                    //   jQuery.ajax({
                    //        url: "? cmd=UserRegister",   // 提交的页面
                    //        data: $('#fmUserRegister').serialize(), // 从表单中获取数据
                    //        type: "POST",                   // 设置请求类型为"POST"，默认为"GET"
                    //        beforeSend: function ()          // 设置表单提交前方法
                    //        {
                    //           // new screenClass().lock();
                    //        },
                    //        error: function (request) {      // 设置表单提交出错
                    //            //new screenClass().unlock();
                    //            alert("表单提交出错，请稍候再试");
                    //        },
                    //        success: function (data) {
                    //            //new screenClass().unlock(); // 设置表单提交完成使用方法
                    //            alert(data);
                    //        }
                    //    });
                    //},
                    //"关闭": function () {
                    //    $("#myOrderDialog").dialog("close");
                    //}
                }

            }).hover(function(){$(this).addClass("hover");},function () {
				$(this).removeClass("hover");
                $(this).dialog("close");
            }).dialog('widget').find(".ui-dialog-titlebar").hide();
           // $("#myOrderDialog").dialog('widget').find(".ui-dialog-buttonpane").hide();
            $("#myOrderDialog").dialog("open");
            __refactor__ = false;
        }//function
    }//open

}
