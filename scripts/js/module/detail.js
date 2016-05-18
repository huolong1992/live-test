//点击开始笔试
$('#detail_begin').click(function(){
    var me = $(this);
    var date = new Date();
    if(date.getTime() < me.attr('data-start')){
        LT.alert('笔试未开始, 请耐心等待');
        return ;
    }
    var data = {
        subject_id: me.attr('data-id')
    };
    $.ajax({
        url: LT.host + 'subject/begin',
        type: 'post',
        data: JSON.stringify(data),
        success: function(data){
            data = JSON.parse(data);
            if(data.status == 503){
                location.href = LT.host + 'userview/log';
                return ;
            }
            if(data.success){
                location.href = LT.host + 'subjectview/subinfo/' + me.attr('data-id');
            }else{
                LT.alert(data.error_info);
            }
        }
    });
});