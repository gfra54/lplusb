var _ratio = 1.457516339869281;

$(function() {
	$('#content .cell').each(function(){
		$(this).height($(this).width()/_ratio);
		$(this).attr('trt','ok');
	})

	
});
