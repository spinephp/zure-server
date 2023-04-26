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

var validateUserName = function(username){
    if (/^[a-zA-Z]{1}[a-zA-Z0-9\-\_\@\.]{4,16}[a-zA-Z0-9]{1}$/.test(username)) {
        //用户名不能以数字开头
        return 1;
    }else
        return 0;
}

/** AJAX 检查用户名是否存在，如用户名存在，用绿色在 username_err_info 指定处显示"通过"，
 * 否则用红色在 username_err_info 指定处显示"用户名已存在"或其它错误信息。
 * @param string value - 包含用户名的字符串
 */
var checkUserName = function (value) {
    var param = $.param({filter:["username"], cond: [{ field:"username",value:value,operator:"eq" }], token: sessionStorage.token });
    var url = "? cmd=Person&" + param;
	$.getJSON(url, null, function (result) {
	    var clTxt = "red";
	    if (result instanceof Array) {
	        if (result.length == 0) {
	            info = "通过";
	            clTxt = "green";
	        } else
	            info = "用户名已存在";
	    }else {
	        info = result;
	    }
		$("#username_err_info").css("color",clTxt);
		$("#username_err_info").html(info);  
	});
}

var testpass = function(password, username) {
    var score = 0;
    if (password.length < 4) { return -4; }
    if (typeof (username) != 'undefined' && password.toLowerCase() == username.toLowerCase()) { return -2 }
    score += password.length * 4;
    score += (repeat(1, password).length - password.length) * 1;
    score += (repeat(2, password).length - password.length) * 1;
    score += (repeat(3, password).length - password.length) * 1;
    score += (repeat(4, password).length - password.length) * 1;
    if (password.match(/(.*[0-9].*[0-9].*[0-9])/)) { score += 5; }
    if (password.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/)) { score += 5; }
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) { score += 10; }
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) { score += 15; }
    if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([0-9])/)) { score += 15; }
    if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([a-zA-Z])/)) { score += 15; }
    if (password.match(/^\w+$/) || password.match(/^\d+$/)) { score -= 10; }
    if (score < 0) { score = 0; }
    if (score > 100) { score = 100; }
    return score;
}
var repeat = function(len, str) {
    var res = "";
    for (var i = 0; i < str.length; i++) {
        var repeated = true;
        for (var j = 0, max = str.length - i - len; j < len && j < max; j++) {
            repeated = repeated && (str.charAt(j + i) == str.charAt(j + i + len));
        }
        if (j < len) repeated = false;
        if (repeated) {
            i += len - 1;
            repeated = false;
        }
        else {
            res += str.charAt(i);
        }
    }
    return res;
}

var checkpass = function(pass,username) {
    var user;
    if (username) {
        user = username;
    } else {
        user = "usrname";
    }
    var score = testpass(pass, user);
    var password_label = $('#password_label');
    if (score == -4) {
        password_label.html("<span style='height:20px;line-height:20px;display:block;'>太短</span>");
    } else if (score == -2) {
        password_label.html("<span style='height:20px;line-height:20px;display:block;'>与用户名相同</span>");
    } else {
        var color = score < 34 ? '#edabab' : (score < 68 ? '#ede3ab' : '#d3edab');
        var text = score < 34 ? '弱' : (score < 68 ? '一般' : '很好');
        var width = score + '%';
        password_label.html("<span style='width:" + width + ";display:block;overflow:hidden;height:20px;line-height:20px;background:" + color + ";'>" + text + "</span>");
    }
}

var registerDialog = function () {
    var __refactor__ = false;//是否需要重新构建 该对话框
    return {
        open: function (options) {
            if (__refactor__) {
                $("#registDialog").remove();
            }
            if ($("#registDialog").size() > 0) {
                $("#registDialog").dialog("open");
                return;
            }

            var buffer = new StringBuilder();
            buffer.append("<div id='registDialog'>");
            buffer.append("<form id='fmUserRegister'>");
            buffer.append("<dl>");
            buffer.append("<dt><label for='UserName'>用户名:</label></dt><dd><input name='UserName' type='text' required placeholder='6~18个字符(a-zA-Z0-9.-_@)' /><span>*</span> <span id='username_rule'><button id='verifyUserName'>检测</button> </span><span id='username_err_info'></span></dd>")
            buffer.append("<dt><label for='Password'>键入密码:</label></dt><dd><input name='Password' type='password' required placeholder='6-16 位字符(a-zA-Z0-9.!@#$%^&*?_~)'/></dd><dd><span>*</span><span id='password_err_info'></span></dd>")
            buffer.append("<dt><label >密码强度:</label></dt><dd id='password_label' style='width:245px;border:1px solid #ccc;margin-top:3px;'><span style='width:150px;height:20px;display:block;border:1px solid #F0F0F0'> </span></dd>")
            buffer.append("<dt><label for='PasswordAgain'>再次键入密码:</label></dt><dd><input name='PasswordAgain' type='password' required placeholder='同上'/></dd><dd><span>*</span><span id='passwordagain_err_info'></span></dd>")
            buffer.append("<dt><label for='Email'>电子邮箱:</label></dt><dd><input name='Email' type='email' required placeholder='如:abc@example.com'/></dd><dd><span>*</span><span id='email_err_info'></span></dd>")
            buffer.append("<dt><label for='Mobile'>手机:</label></dt><dd><input name='Mobile' type='text' placeholder='如:18961376627' /></dd><dd><span>*</span><span id='mobile_err_info'></span></dd>")
            buffer.append("<dt><label for='Tel'>固定电话:</label></dt><dd><input name='Tel' type='tel' required placeholder='如:+86 518 82340137'/></dd><dd><span>*</span><span id='tel_err_info'></span></dd>")
            buffer.append("<dt><label for='Validate'>验证码:</label></dt><dd><input name='code' type='text' required placeholder='输入右侧图片中的字符'/></dd><dd><span>* </span><img id='validate' src='admin/checkNum_session.php' align='absmiddle' style='border:#CCCCCC 1px solid; cursor:pointer;' alt='点击重新获取验证码' width=50 height=20 /></dd>")
            buffer.append("</dl>");
            buffer.append("<input type='hidden' name='action' value='custom_register' />");
            buffer.append("<input type='hidden' name='LastTime' value='' />");
            buffer.append("<input type='hidden' name='Times' value='0' />");
            buffer.append("<input type='hidden' name='token' value=" + sessionStorage.token + " />");
            buffer.append("</form>");
            buffer.append("</div>");

            $(buffer.toString()).appendTo("body");
			
            $("#validate").attr('title', '点击重新获取验证码');

            $("#fmUserRegister input[name=Password]").keyup(function () {
                checkpass($(this).val(), $("#fmUserRegister input[name=UserName]").val());
            });

                // 用户名检测按键处理程序
			$("#verifyUserName").click(function(){
			    var value = $("#fmUserRegister input[name=UserName]").val();
				    ret = validateUserName(value);
				if (ret==1){
				    checkUserName(value);
				}
			});
			
			// 用户注册表输入框获得焦点时处理程序
			$("#fmUserRegister dl").delegate("dd input","focus",function(){
			    var name = $(this).attr("name"),
				    value = $(this).val(),
					basename = name.substr(6).toLowerCase(),
                    errinfo = $("#" + basename + "_err_info");
			    if(errinfo) errinfo.hide();
				return false;
			});

			$("#validate").click(function () {
			    this.src="admin/checkNum_session.php?"+Math.ceil(Math.random()*1000);
			});

			// 用户注册表输入框失去焦点时处理程序
			$("#fmUserRegister dl").delegate("dd input","blur",function(){
			    var name = $(this).attr("name"),
				    value = $(this).val(),
					errUserName = ["用户名不能为空", "用户名不能以数字开头", "合法长度为6-18个字符", "用户名只能包含_,英文字母,数字 ", "用户名只能英文字母或数字结尾"],
					basename = name.toLowerCase(),
                    errinfo = $("#" + basename + "_err_info"),
                    ret,
					info;
			    if(errinfo) errinfo.show();
			    switch (basename) {
				    case "username":
					    ret = validateUserName(value);
						if (ret==1){ // AJAX 查询用户名是否存在
						    checkUserName(value);
						    return true;
						}
						else{// 显示用户名输入框错误信息
							info = "无效的用户名！";  
						} 
						break;
					case "password":
					case "passwordagain":
						info = /^[\w\-\!\@\#\$\%\^\&\*]{6,16}$/.test(value)? "通过":"密码格式错误";
					    break;
					case "email":
					    info = /^\w+((-\w+)|(\.\w+))*\@\w+((\.|-)\w+)*\.\w+$/.test(value)? "通过":"邮箱格式错误";
						break;
					case "mobile":
					    info = /^1[3|4|5|8]\d{9}$/.test(value)? "通过":"无效的手机号码";
						break;
					case "tel":
					    info = /^((\+\d{2,3}[ |-]?)|0)\d{2,3}[ |-]?\d{7,9}$/.test(value)? "通过":"无效的电话号码";
						break;
				}
				errinfo.css("color",(info=="通过"? "green":"red"));
				errinfo.html(info);  
				if(info!="通过"){
					$(this).focus();
					return false;
				}
			});

            $("#registDialog").dialog({
                autoOpen: false,
                closeOnEscape: true,
                width: '600px',
                modal: true,
                title: "用户注册",
                buttons: {
                    "注册": function () {
                        __refactor__ = true;
						var pwd1 = $("#fmUserRegister input[name=Password]"),
						    pwd2 = $("#fmUserRegister input[name=PasswordAgain]");
						if(pwd1.val()!=pwd2.val()){
						    alert("分别键入的两个密码不一致!\n请重新输入。");
							pwd1.val("");
							pwd2.val("");
							pwd1.focus();
							return false;
						}
                        jQuery.ajax({
                            url: "? cmd=UserRegister&token="+sessionStorage.token,   // 提交的页面
                            data: $('#fmUserRegister').serialize(), // 从表单中获取数据
                            type: "POST",                   // 设置请求类型为"POST"，默认为"GET"
                            beforeSend: function ()          // 设置表单提交前方法
                            {
                               // new screenClass().lock();
                            },
                            error: function (request) {      // 设置表单提交出错
                                //new screenClass().unlock();
                                alert("表单提交出错，请稍候再试");
                            },
                            success: function (data) {
                                var obj = JSON.parse(data);
                                //new screenClass().unlock(); // 设置表单提交完成使用方法
                                $("#registDialog").dialog("close");
                                alert("恭喜您，"+obj.register+"\n\n"+obj.email);
                            }
                        });
                    },
                    "取消": function () {
                        $("#registDialog").dialog("close");
                    }
                }

            });
            $("#registDialog").dialog("open");
            __refactor__ = false;
        }//function
    }//open
}
