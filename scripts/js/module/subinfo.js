LT.cur = 1;//记录当前题目序号
LT.cnt = $('.subinfo-content').length;//记录题目的个数
LT.subinfo = {subinfo: []};//记录用户提交的答案
//点击下一题
$('#subinfo_next').click(function(){
	if(LT.cur == LT.cnt){
	    if(!LT.confirm('已经是最后一题, 点确定交卷?')){
	        return ;
	    }
	    post_form();
	    return ;
	}
	$('#' + LT.cur).hide();
	LT.cur++;
    $('#' + LT.cur).show();
});
//点击交卷
$('#subinfo_post').click(function(){
    if(LT.cur != LT.cnt){
        if(!LT.confirm('您尚未完成笔试, 确定交卷?')){
            return ;
        }
        post_form();
        return ;
    }
    post_form();
});
//交卷处理
function post_form(){
    $('.subinfo-content').each(function(){
    	 var answer = '';
    	 var radio = $(this).find('input[type="radio"]:checked');
    	 var checkbox = $(this).find('input[type="checkbox"]:checked');

    	 if(radio.attr('data-id')){
    	     answer = radio.attr('data-id');
    	 }else{
    	     checkbox.each(function(){
    	         answer += $(this).attr('data-id') + ',';
    	     });
    	     answer = answer.substr(0, answer.length-1);
    	 }
        var _data = {
		     subinfo_id: $(this).attr('data-id'),
		     answer: answer
		 };
		 LT.subinfo.subinfo.push(_data);
    });
    var data = deepClone(LT.subinfo);//这里须深拷贝
    LT.subinfo.subinfo = [];
    $.ajax({
        url: LT.host + 'subject/finish',
        type: 'post',
        data: JSON.stringify(data),
        success: function(data){
            data = JSON.parse(data);
            if(data.status == 503){
                location.href = LT.host + 'subjectview/company';
                return ;
            }
            if(data.success){
                location.href = LT.host + 'subjectview/success';
            }else{
                LT.alert(data.error_info);
            }
        }
    });
}
//剩余时间
var interval = setInterval(function(){
    $('#subinfo_time_s').text($('#subinfo_time_s').text() - 1);
    if($('#subinfo_time_s').text() < 0){
        $('#subinfo_time_s').text('59');
        $('#subinfo_time_m').text($('#subinfo_time_m').text() - 1);
        if($('#subinfo_time_m').text() < 0){
            $('#subinfo_time_m').text('59');
            $('#subinfo_time_h').text($('#subinfo_time_h').text() - 1);
            if($('#subinfo_time_h').text() < 0){
                post_form();
            }
        }
    }
}, 1000);
//深拷贝
function deepClone(obj) {
	 if  (!obj || typeof obj!= "object" || typeof obj == "function") {
		 return obj;
	 }
	 var res = {}.toString.call(obj) == "[object Array]" ? [] : obj.constructor ? new obj.constructor() : {};
	 for (var key in obj) {
		 res[key] = res[key] ? res[key] : deepClone(obj[key]);
	 }
   return res;
}