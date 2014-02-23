var _prec_wrap=false;
$(window).load(function() {
		initGlobal();

	var _prec_hash=false
	setTimeout(function(){setInterval(function(){
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

	},100)},2000);
});

function initGlobal(){
		/**
		 * Chargement des liens pas slide in
		 */
		$('.slidin').click(function(e){
			var _href=$(this).attr('href');
			if(_href && _href.substr(0,1)!='#') {
				e.preventDefault();
				$.get(_href+'?ajax',function(data){
					$('<div id="tmp_page">'+data+'</div>').appendTo('body');
					$('#tmp_page').css({
						'left':$(window).width()+'px',
						'width':$(window).width()+'px',
						'min-height':$(window).height()+'px',
						'display':'block',
						'background-color':$('body').css('background-color')
					}).animate({'left':0},'slow',function(){
						$('body').html($(this).html());
						$(this).remove();
						document.location.hash='';
						initGlobal();

					});
				});
			}
		});

		/*
		Au chargement de la page, recadrer les photos dans les cells
		 */
		var _cell_ratio = $('.cell').width()/$('.cell').height();
		$('.cell IMG').each(function(){
				var _img_ratio = ($(this).width()/$(this).height());
				if(_img_ratio>_cell_ratio){
					$(this).css({'height':'100%','width':'auto'});
				}
		});

		/*
		Replier le menu apres chargement de la page
		 */
		setTimeout(function(){
			$('#nav .wrap').each(function(){
				if(!$(this).hasClass('visible')){
					$(this).animate({'height':0},'slow');
				} else {
					_prec_wrap = $(this);
				}
				$(this).attr('data-height',$(this).height());
			});
		},2000);

		/*
		Remonter doucement en faut de la page
		 */
		$('.toup').click(function(){
	        $('html, body').animate({scrollTop: 0}, 1000);
		});

		/*
		gestion des clicks sur le menu
		 */
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

		/*
		slider d'images
		 */
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
}