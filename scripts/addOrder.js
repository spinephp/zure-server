//
// Replace prototype
//
String.prototype.format = function () {
    var args = arguments;
    return this.replace(/\{(\d)\}/g, function (a, b) {
        return typeof args[b] != 'undefined' ? args[b] : a;
    });
}

//
// StringBuilder class using join
//

// Initializes a new instance of the StringBuilder class
// and appends the given value if supplied
function StringBuilder(value) {
    this.strings = new Array("");
    this.append(value);
}

// Appends the given value to the end of this instance.
StringBuilder.prototype.append = function (value) {
    if (value) {
        this.strings.push(value);
    }
}

// Clears the string buffer
StringBuilder.prototype.clear = function () {
    this.strings.length = 1;
}

// Converts this instance to a String.
StringBuilder.prototype.toString = function () {
    return this.strings.join("");
}

var addOrderDialog = function () {
    var __refactor__ = true;//是否需要重新构建 该对话框
    return {
        open: function (options) {
            var dlgAddOrder = $("#addOrderDialog");
            if (__refactor__) {
                dlgAddOrder.remove();
            }

            var buffer = new StringBuilder();
            buffer.append("<div id='addOrderDialog'>");
            buffer.append("<img src='images/Ok.png' width=48 height=48 />");
            buffer.append("<h3>订单添加成功！</h3>");
            buffer.append("<p>订单包含 "+options.kind+" 种产品，合计：¥<span>"+options.price+"</span></p>")
            buffer.append("</div>");

            $(buffer.toString()).appendTo("body");
			

            $("#addOrderDialog").dialog({
                autoOpen: false,
                closeOnEscape: true,
                width: '400px',
                modal: true,
                title: "添加订单",
                buttons: {
                    "订单结算": function () {
                        __refactor__ = true;
						location.href = "? cmd=ShowOrder&token="+sessionStorage.token;
                    },
                    "关闭": function () {
                        $("#addOrderDialog").dialog("close");
                    }
                }

            });
            $("#addOrderDialog").dialog("open");
            __refactor__ = false;
        }//function
    }//open
}
