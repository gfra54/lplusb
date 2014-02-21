var _prec_wrap=false;
$(window).load(function() {
		$('#nav').fadeIn('slow',function(){
			$('#content').fadeIn('slow',function(){
					$('#nav .wrap').each(function(){
						if(!$(this).hasClass('visible')){
							$(this).animate({'height':0},'slow');
						} else {
							_prec_wrap = $(this);
						}
						$(this).attr('data-height',$(this).height());
					});
			});
		});

	$('#nav A.main').click(function(e){
		e.preventDefault();
		var _wrap = $(this).next();
		_wrap.stop().animate({'height':_wrap.attr('data-height')},'slow',function(){
			if(_prec_wrap){
				_prec_wrap.stop().animate({'height':0},'slow',function(){
					_prec_wrap=_wrap;
				});
			} else {
				_prec_wrap=_wrap;
			}
		});

	});
	$('#slider').attr('data-ml',0).attr('data-moving',0);
	$('#slider #sliding').click(function(){
		$('#slider #next').click();	
	});
	$('#slider #prev').click(function(){

		var _ml = Number($('#slider').attr('data-ml')) + $('#slider .slide').width();
		if(_ml<=0){
			$('#slider').attr('data-ml',_ml).attr('data-moving',1);
			$('#slider #zone #sliding').stop().animate({'margin-left':_ml},'slow',function(){
				$('#slider').attr('data-moving',0);
			});
		}
	});
	$('#slider #next').click(function(){
		var _ml = Number($('#slider').attr('data-ml')) - $('#slider .slide').width();
		if(_ml>=-$('#slider .slide').width()*($('#slider .slide').length-1)){
			$('#slider').attr('data-ml',_ml).attr('data-moving',1);
			$('#slider #zone #sliding').stop().animate({'margin-left':_ml},'slow',function(){
				$('#slider').attr('data-moving',0);
			});
		} 
	});

	$('#slider').mousewheel(function(event, delta, deltaX, deltaY) {
			event.preventDefault();
			if($('#slider').attr('data-moving') != 1){
				if(delta<0){
					$('#slider #next').click();
				} else {
					$('#slider #prev').click();
				}
			}
	});	
});