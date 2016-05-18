//当前选项卡
var target = $('#left').attr('data-target');
$('#left_' + target).addClass('left-active');
$('#left_' + target + ' a').css({color: '#FFF'});