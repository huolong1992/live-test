//日期, 时间控件
var start_date = $('#start_date').pickadate({
	clear: '',
	format: 'yyyy-mm-dd'
});
var end_date = $('#end_date').pickadate({
	clear: '',
	format: 'yyyy-mm-dd'
});
var start_time = $('#start_time').pickatime({
	format: 'H:i'
});
var end_time = $('#end_time').pickatime({
	clear: '',
	format: 'H:i'
});
//保存试题的对象
var Subject = {subinfo: []};
//点击下一步
$('#pub_next').click(function(){
    var data = check_form('pub_1');
    if(!data){
        return ;
    }
    Subject.subject = data;
    $('#pub_1').hide();
    $('#pub_2').show();
    $(this).hide();
    $('#pub_next_subject').show();
    $('#pub_post').show();
});
//点击下一题
$('#pub_next_subject').click(function(){
    var data = check_form('pub_2');
    if(!data){
        return ;
    }
    Subject.subinfo.push(data);
    $('#pub_2 input').val('');
    $('.pub-1-item-new').remove();
});
//点击提交表单
$('#pub_post').click(function() {
    var data = check_form('pub_2');
    if(!data){
        return ;
    }
    Subject.subinfo.push(data);
    $.ajax({
        url: LT.host + 'subject/pub',
        type: 'post',
        data: JSON.stringify(Subject),
        success: function(data){
            data = JSON.parse(data);
            if(data.status == 503){
                location.href = LT.host + 'userview/log';
                return ;
            }
            if(data.success){
                location.href = LT.host + 'subjectview/published';
            }else{
                LT.alert(data.error_info);
            }
        }
    });
});
//表单验证
function check_form(target){
    if(target == 'pub_1'){
        var name = $('#name').val();
        var token = $('#token').val();
        var start_date = $('#start_date').val();
        var start_time = $('#start_time').val();
        var end_date = $('#end_date').val();
        var end_time = $('#end_time').val();
        var tips = $('#tips').val();
        var mail_list = $('#mail_list').val();
        //验证
        //code here ....
        var data = {
            name: name,
            token: token,
            start_time: start_date + ' ' + start_time,
            end_time: end_date + ' ' + end_time,
            tips: tips,
            mail_list: mail_list
        };
        return data;
    }else{
        var type = $('#type').val();
        var skill = $('#skill').val();
        var title = $('#title').val();
        var score = $('#score').val();
        var option = '';
        var cnt = 1;
        $('.option').each(function(){
            option += cnt + ', ' + $(this).val() + '#@#';
            cnt++;
        });
        option = option.substr(0, option.length-3);
        var right_answer = $('#right_answer').val();
        //验证
        //code here
        var data = {
            type: type,
            skill: skill,
            title: title,
            score: score,
            option: option,
            right_answer: right_answer
        };
        return data;
    }
}
//点击添加选项
$('#pub_add').click(function(){
    var parent = $(this).parent().parent();
    var option_index = parent.prev().find('label').text();
    option_index = parseInt(option_index.substr(-1)) + 1;
    var pub_item = '<div class="pub-1-item pub-1-item-new"><label for="option_' + option_index + '">选项' + option_index + '</label><div class="pub-1-input"><input type="text" id="option_' + option_index + '" class="option"><span class="input-tip"></span></div></div>';
    parent.before(pub_item);
});