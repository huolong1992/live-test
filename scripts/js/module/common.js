window.LT = {
    alert: function(data){
        alert(data);
    },
    confirm: function(data){
        if(confirm(data)){
            return true;
        }
        return false;
    },
    host: 'http://localhost/live-test/'
};
//鼠标经过用户中心
$('.user-center').mouseover(function(){
    $('.user-center-sub').show();
});
$('.user-center').mouseout(function(){
    $('.user-center-sub').hide();
});