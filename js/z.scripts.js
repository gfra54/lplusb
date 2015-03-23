var _prec_wrap=false;
$(document).ready(function() {

	/*
	Remonter doucement en haut de la page
	 */
	$('.toup').click(function(){
        $('html, body').animate({scrollTop: 0}, 1000);
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
	},1000);


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


});