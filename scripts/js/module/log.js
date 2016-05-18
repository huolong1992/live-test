//根据target判断是登录还是注册
var target = $('#log').attr('target');
if(target == 2){
	login2register();
}else{
	register2login();
}
//个人和公司
var is_com = $('input[name="user_type"]:checked').val()=='company' ? true : false;
$('input[name="user_type"]').change(function(){
    if($(this).val() == 'company'){
        $('.company').show();
    }else if($(this).val() == 'person'){
        $('.company').hide();
    }
});
//刷新验证码
$('#code_img_login').click(function(){
	$(this).attr('src', '/live-test/user/get_code?rand=' + Math.random());
});
$('#code_img_register').click(function(){
	$(this).attr('src', '/live-test/user/get_code?rand=' + Math.random());
});
//登录和注册切换
$('#log_login').click(function(){
	register2login();
});
$('#log_register').click(function(){
	login2register();
});
//登录和注册切换
function login2register(){
	$('#log_content_login').hide();
	$('#log_content_register').show();
	$('#log_login').removeClass('log-header-active');
	$('#log_register').addClass('log-header-active');
}
function register2login(){
	$('#log_content_login').show();
	$('#log_content_register').hide();
	$('#log_register').removeClass('log-header-active');
	$('#log_login').addClass('log-header-active');
}
//点击注册
$('#submit_register').click(function(){
	 var data = check_form('register');
    if(!data){
        return ;
    }
    $.ajax({
        url: LT.host + 'user/register',
        type: 'post',
        data: data,
        success: function(data){
            data = JSON.parse(data);
            if(data.status == 503 || data.success){
                location.href = LT.host + 'subjectview/company';
                return ;
            }
            LT.alert(data.error_info);
        }
    });
});
//点击登录
$('#submit_login').click(function(){
    var data = check_form('login');
    if(!data){
        return ;
    }
    $.ajax({
        url: LT.host + 'user/login',
        type: 'post',
        data: data,
        success: function(data){
            data = JSON.parse(data);
            if(data.status == 503 || data.success){
                location.href = LT.host + 'subjectview/company';
                return ;
            }
            LT.alert(data.error_info);
        }
    });
});
//表单验证
function check_form(target){
    if(target == 'register'){
        //注册
        var is_com = $('input[name="user_type"]:checked').val()=='company' ? true : false;
    	 if(is_com){
    	 	var company = $('#company');
    	 	var icp = $('#icp');
    	 	if(company.val() == ''){
               company.next().show();
               company.focus();
               return false;
           }else{
               company.next().hide();
           }
           if(icp.val() == ''){
               icp.next().show();
               icp.focus();
               return false;
           }else{
               icp.next().hide();
           }
    	 }
        var mail = $('#mail_register');
        var phone = $('#phone');
        var pass = $('#pass_register');
        var pass_confirm = $('#pass_confirm');
        var code = $('#code_register');
        if(mail.val() == ''){
            mail.next().show();
            mail.focus();
            return false;
        }else{
            mail.next().hide();
        }
        if(phone.val() == ''){
            phone.next().show();
            phone.focus();
            return false;
        }else{
            phone.next().hide();
        }
        if(pass.val() == ''){
            pass.next().show();
            pass.focus();
            return false;
        }else{
            pass.next().hide();
        }
        if(pass_confirm.val() != pass.val()){
            pass_confirm.next().show();
            pass_confirm.focus();
            return false;
        }else{
            pass_confirm.next().hide();
        }
        if(code.val() == ''){
            code.next().next().show();
            code.focus();
            return false;
        }else{
            code.next().next().hide();
        }
        var data = {
    	     mail: mail.val(),
    	     phone: phone.val(),
    	     pass: pass.val(),
    	     code: code.val()
    	 };
    	 if(is_com){
    	     data.is_com = 1,
    	     data.company = company.val(),
    	     data.icp = icp.val()
    	 }
    	 return JSON.stringify(data);

    }else{
        //登录
        var mail = $('#mail_login');
        var pass = $('#pass_login');
        var code = $('#code_login');
        if(mail.val() == ''){
            mail.next().show();
            mail.focus();
            return false;
        }else{
            mail.next().hide();
        }
        if(pass.val() == ''){
            pass.next().show();
            pass.focus();
            return false;
        }else{
            pass.next().hide();
        }
        if(code.val() == ''){
            code.next().next().show();
            code.focus();
            return false;
        }else{
            code.next().next().hide();
        }
        var data = {
            mail: mail.val(),
            pass: pass.val(),
            code: code.val()
        };
        return JSON.stringify(data);
    }
}