var _prec_wrap=false;
$(window).load(function() {
		setTimeout(function(){$('.cell IMG').each(function(){
			if($(this).width()>$(this).height()){
				$(this).css({'height':'100%','width':'auto'});
			}
		})
		},1500);
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
	$('#slider').attr('data-rang',1).attr('data-moving',0);
	var _cadre=false;
	var _fond=false;
	$('#slider #sliding LI').click(function(){
			$('<div id="cadre"></div>').appendTo('body');
			$('<div id="fond"></div>').appendTo('body');
			_cadre = $('#cadre');
			_fond = $('#fond');
			$('#cadre, #fond').css({
				height: $(window).height(),
				lineHeight: $(window).height()+'px',
				width: $(window).width(),
			}).click(function(){
				$('#cadre, #fond').fadeOut('slow',function(){
					$('body').css({'overflow':'auto'});
					$('#cadre, #fond').remove();				
				})				
			});
			$(window).resize(function(){
				$('#cadre, #fond').css({
					height: $(window).height(),
					lineHeight: $(window).height()+'px',
					width: $(window).width(),
				});
			})

		$('body').css({'overflow':'hidden'});
		_cadre.html($(this).html());
		// _cadre.html($(this).html().replace('y500','y1000'));
		_fond.fadeIn('slow',function(){
			_cadre.fadeIn('slow',function(){
			});
		});

	});
	$('#slider #prev').click(function(){
		var _rang = Number($('#slider').attr('data-rang'))-1;
		console.log(_rang)
		if(_rang>0){
			$('#slider').attr('data-rang',_rang);
			document.location.hash=_rang == 1 ? '' : '#i'+_rang;
		}
	});
	$('#slider #next').click(function(){
		var _rang = Number($('#slider').attr('data-rang'))+1;
		console.log(_rang)
		if(_rang<=$('#slider .slide').length){
			$('#slider').attr('data-rang',_rang);

			document.location.hash=_rang == 1 ? '' : '#i'+_rang;
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
	var _prec_hash=false
	setInterval(function(){
		if(document.location.hash != _prec_hash){
			_prec_hash = document.location.hash;
			var _new_rang = _prec_hash ? Number(_prec_hash.replace('#i','')) : 1;
			_ml = (_new_rang-1) * ($('#slider .slide').width()) * -1;
			
			$('#slider').attr('data-moving',1);
			$('#slider').attr('data-rang',_new_rang);
			$('#slider #zone #sliding').stop().animate({'margin-left':_ml},'slow',function(){
				$('#slider').attr('data-moving',0);
			});

		}

	},100)	
});