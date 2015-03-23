document.getElementById('content').style.opacity=0;

$(document).ready(function() {
	var _content_width = $('#content').width();
	var _margin_cell = 15;
	var _scan=1;
	var _lines=0;
	var _prec_top = 0;

	$('#content .cell').each(function(){
		$(this).css('margin-left',_margin_cell);
		var _top = $(this).offset().top;
		if(_top != _prec_top) {
			_lines++;
			$(this).css('margin-left',0);
		}
		$(this).addClass('line-'+_lines);
		_prec_top = _top;
	});

	// console.log('_content_width '+_content_width);


	for(_i=1;_i<=_lines;_i++){
		var _max=500;
		var _line_width = 0;
		var _delta = 0;
		while(_line_width < _content_width && (_max-- > 0)) {
			_delta+=_scan;
			var _line_width = 0;
			$('.cell.line-'+_i).each(function(){
				var _ratio = $(this).data('ratio');
				var _tmp_h = $(this).height()+_delta;
				var _tmp_w = Math.round(_ratio*_tmp_h);

				if(_line_width>0) {
					_line_width+=_margin_cell;
				}
				_line_width+=_tmp_w;
			})
		}
		_delta-=_scan;
		if(_delta && $('.cell.line-'+_i).length>1) {
			// console.log('_delta l'+_i+' '+_delta);
			$('.cell.line-'+_i).each(function(){
				var _ratio = $(this).data('ratio');
				var _new_h = $(this).height()+_delta;
				var _new_w = Math.round(_ratio*_new_h);
				$(this).width(_new_w);
				$(this).height(_new_h);
			});
		}
	}
	$('#content').animate({'opacity':1},1000);
});
$(window).load(function() {

});