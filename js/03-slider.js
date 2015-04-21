var _slide = false;
var _opened = false;
$(document).ready(function() {
	var _prec_hash=false
	setTimeout(function(){setInterval(function(){
		if(document.location.hash != _prec_hash){
			$('#slider .slide').width($('#slider').width());
			_prec_hash = document.location.hash;
			_tab = _prec_hash.replace('#i','').split('-');
			_tmp = Number(_tab[0]);
			_opened = _tab.length>1 && _tab[1] == 'opened';

			var _new_rang = _prec_hash ? _tmp : 1;
			_ml = (_new_rang-1) * ($('#slider').width()) * -1;
			// console.log($('#slider').width())
			$('#slider').attr('data-moving',1);
			$('#slider').attr('data-rang',_new_rang);

			$('.legende').addClass('legende-hidden');

			if($('.legende-'+_new_rang).length) {
				$('.legende-'+_new_rang).removeClass('legende-hidden');
			} else {
				$('.legende-0').removeClass('legende-hidden');
			}
			$('#slider #zone #sliding').stop().animate({'margin-left':_ml},1000,function(){
			//	$('#description .count').html($('#slider').attr('data-rang')+'/'+$('#slider .slide').length);
				$('#slider').attr('data-moving',0);
				if(_opened) {
					_opened=false;
					toggleModeImage();
				}
			});

		}

	},50)},1000);

/*	$('#slider .slide').click(function(){
		_slide = $(this);
		if(!_slide.hasClass('opened')) {
			_hash = '#i'+$('#slider').attr('data-rang')+'-opened';
		} else {
			_hash = '#i'+$('#slider').attr('data-rang');
			toggleModeImage();
		}
		document.location.hash = _hash;
	});*/

	$('#slider').attr('data-rang',1).attr('data-moving',0);

	$('#slider').on('flick', function(e) {
	    if ('horizontal' == e.orientation) {
	        if (1 == e.direction) {
				$('#slider #next').click();
	        } else {
				$('#slider #prev').click();
	        }
	    }
	});

	$('#slider #prev').click(function(){
		var _rang = Number($('#slider').attr('data-rang'))-1;
		// console.log(_rang)
		if(_rang>0){
			$('#slider').attr('data-rang',_rang);
			document.location.hash=_rang == 1 ? '' : '#i'+_rang;
		}
	});
	$('#slider #next').click(function(){
		var _rang = Number($('#slider').attr('data-rang'))+1;
		// console.log(_rang)
		if(_rang<=$('#slider .slide').length){
			$('#slider').attr('data-rang',_rang);

			document.location.hash=_rang == 1 ? '' : '#i'+_rang;
		} 
	});
});


function toggleModeImage(){
	$('#slider .slide:eq('+($('#slider').attr('data-rang')-1)+')').toggleClass("opened");
	$("body").toggleClass("mode-image");
}