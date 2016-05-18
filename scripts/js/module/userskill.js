var map = ['算法', '网络', '操作系统', '编程语言', 'Linux'];
var data = [];
$('.userskill-data').each(function(){
	var key = $(this).attr('data-id') - 1;
	var score = parseInt($(this).attr('data-score')) + 1;
	data.push({label: map[key], data: score});
});
var options = {
	series: {
	    pie: {
	        show: true,
	        combine: {
	            color: '#999',
	            threshold: 0.01
	        }
	    }
	},
	legend: {
		show: false
	}
};
$.plot('#skill', data, options);