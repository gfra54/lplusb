$(document).ready(function() {
	$(".fancybox").fancybox();


});

function initUploadify(_w,_id) {
    $('#file_upload').uploadify({
        'swf'      : 'uploadify/uploadify.swf',
        'uploader' : 'uploadify/uploadify.php',
		'method'   : 'post',
		'formData' : { 'what' : _w,'id':_id },
		'onQueueComplete':function(){ 
			$('#form_upload').submit();
		},
	});
}

function sel(_w) {
	_i=0;
	while(_obj = g('check_'+_i)) {
		_i++;
		_obj.checked=_w == 'all' ? true : false;
	}
}
	
	function setViewed(_id){
	g('lien_press_'+_id).style.color='#0031FF';
}
/*
_prec_menu_annee=false;
function filtreAnnee(annee) {
	if(_prec_menu_annee){
		g('press_'+_prec_menu_annee).style.display='none';
		g('lien_menu_'+_prec_menu_annee).style.color='#555';
	}
	g('press_'+annee).style.display='block';
	g('lien_menu_'+annee).style.color='#0031FF';

	_prec_menu_annee=annee;
	presse_afficher_annee(annee);
}

function presse_afficher_annee(_annee){
	_cpt=1;while(_obj = g('annee_'+_cpt)){_cpt++;
		_annee_tmp = _obj.getAttribute('annee');
		if(_annee_tmp == _annee){
			_obj.style.display='block';
		} else {
			_obj.style.display='none';
		}
	}
}

function adjustScroll(){
	setTimeout('window.scrollBy(0,-150)',10);
}
function is_ie(){
	return navigator.appName.indexOf("Microsoft")!=-1;
}
function adjustLeftMargin() {
	if(g('wrapper')){
		if(largeurFenetre()<1200) {
			_margin='10px';
		} else {
			_margin='184px';
		}
		g('top').style.marginLeft=_margin;
		g('menu').style.marginLeft=_margin;
		g('wrapper').style.marginLeft=_margin;
	}
}

function fixedHeader(){
	_header = g('header');
	_wrapper = g('wrapper');
	_left_col = g('left_col');
	_main_col = g('main_col');
	_center_col = g('center_col');

	if(is_ie()){
		_top = (document.documentElement.scrollTop || document.body.scrollTop) + _header.offsetHeight - _header.offsetHeight;
		_header.style.position='absolute';
		_header.style.top = _top 
		_header.style.left = '0px'; 
		_left_col.style.position = 'absolute'; 
		_left_col.style.top = (_top+130)+'px';
		_left_col.style.left = '184px'; 
	} else {
		_header.style.position = 'fixed'; 
		_left_col.style.position = 'fixed'; 
	
	}
	_header.style.backgroundColor='#DEDEDE';	

	_wrapper.style.paddingTop = '135px' 

	if(_main_col)
		_main_col.style.marginLeft = '235px';

	if(_center_col)
		_center_col.style.marginLeft = '235px';

	if(is_ie()){
		setTimeout('fixedHeader()',1/10000);
	}
}

function js_now(_delta) {
	_d = new Date();
	_time = Number(_d.getTime());
	_time += _delta ? Number(_delta)*24*3600*1000 : 0;
	d = new Date(_time);

	

	document.getElementsByName('form[date][d]')[0].value =str_pad(d.getDate(), 2, '0', 'STR_PAD_LEFT');
	document.getElementsByName('form[date][m]')[0].value =str_pad(d.getMonth()+1, 2, '0', 'STR_PAD_LEFT');
	document.getElementsByName('form[date][Y]')[0].value =str_pad(d.getFullYear(), 4, '0', 'STR_PAD_LEFT');

	document.getElementsByName('form[date][H]')[0].value =str_pad(d.getHours(), 2, '0', 'STR_PAD_LEFT');
	document.getElementsByName('form[date][i]')[0].value =str_pad(d.getMinutes(), 2, '0', 'STR_PAD_LEFT');
	

}

_prec_video=false;
function launchVideo(quoi){
	autorun=false;
	if(!_prec_video) {
		_prec_video = g('bigimage').innerHTML;
		g('left_col').style.position='absolute';
		g('header').style.position='absolute';
	}
	
	g('bigimage').innerHTML = g('videocode_'+quoi).innerHTML;
}

cpt=0;
max=0;
_prec_img = false;
function voirImage(quoi){
	if(_prec_video) {
		g('bigimage').innerHTML=_prec_video;
		g('left_col').style.position='fixed';
		g('header').style.position='fixed';
		_prec_video=false;
	}
	if(quoi !='+')
		autorun=false;
	alpha_cacher('bigimage',0);
	setTimeout('alpha_afficher(\'bigimage\',0);',500);
	if(quoi == '+')
		cpt++;
	else if(quoi == '-')
		cpt--;
	else 
		cpt=quoi;

	if(_img = g('img_'+cpt)){
		g('bigimage').style.backgroundImage='url('+_img.getAttribute('img')+')';	
	} else {
		voirImage(0)
	}
	if(	quoi != '+' && quoi != '-'&& quoi != '0'){
		window.scrollTo(0,0);
	}
	tab = document.location.href.split('#');
	document.location.href = tab[0]+'#i_'+cpt;

	if(_prec_img) {
		_class = _prec_img.className;
		_prec_img.className = _class.replace('imgSel','imgNoSel');
	}
	_class = _img.className;
	_img.className = _class.replace('imgNoSel','imgSel');

	_prec_img=_img;
}
_prec_rub=false;
function voirRub(id) {
		if(_prec_rub){
			_prec_rub.style.display='none';
		}
		g('un_projet').style.display='none';
		g('liste_projets').style.display='block';
		_rub = g('rub_'+id)
		_rub.style.display='block';
		_prec_rub = _rub;
		tab = document.getElementsByTagName('a');
		nb=0;
		for(i=0;i<tab.length;i++){
			if(family = tab[i].getAttribute('family')){
				nb++;
				if(family == id)
					tab[i].style.display='block';
				else
					tab[i].style.display='none';
					
			}
		}
}

function loginWindow(){
	g('loginlink').innerHTML=g('login_form').innerHTML;
}
*/
function growTextarea(_id){
	t='500px';
	obj=g(_id);
	if(obj.style.height!=t) {
		obj.setAttribute('tmp_height',obj.style.height);
		obj.style.height=t;
	} else {
		obj.style.height=obj.getAttribute('tmp_height');		
	}
}

function showPreview(_id,force) {
	if(_p = g('preview_'+_id)) {
		_p.innerHTML= nl2br(g(_id).value);
		if(!force) {
			if(_p.style.display!='block') {
				_p.style.display='block';
			} else {
				_p.style.display='none';
			}
		}
	}
}

function showHelp() {
	i=0;
	while(_obj = g('help_'+i)) {
		_obj.style.display='block';
		i++;
	}
}

function delFichier(id){
	if(confirm('Effacer ce fichier ?\n Vous devrez valider le formulaire pour finaliser la suppression des fichiers.')) {
		if(_obj = g('li_'+id)) {
			_obj.style.display='none';
			g('files_to_delete').value += _obj.getAttribute('file')+'|';
		}
	}
}

function switchAllSelect(_select) {
	i=0;
	while(_tmp_select = g('select_'+i)) {
		_element = _tmp_select.getAttribute('element');
		_tmp_onchange = _tmp_select.getAttribute('onchange');
		_tmp_select.setAttribute('onchange','');
		switchLangueInput(_element,_select.value);
		_tmp_select.selectedIndex = _select.selectedIndex;
		_tmp_select.setAttribute('onchange',_tmp_onchange);
		i++;	
	}
}

function g(id) {
	return document.getElementById(id);
}



function nl2br (str, is_xhtml) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Philip Peterson
    // +   improved by: Onno Marsman
    // +   improved by: Atli ??r
    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Maximusya
    // *     example 1: nl2br('Kevin\nvan\nZonneveld');
    // *     returns 1: 'Kevin<br />\nvan<br />\nZonneveld'
    // *     example 2: nl2br("\nOne\nTwo\n\nThree\n", false);
    // *     returns 2: '<br>\nOne<br>\nTwo<br>\n<br>\nThree<br>\n'
    // *     example 3: nl2br("\nOne\nTwo\n\nThree\n", true);
    // *     returns 3: '<br />\nOne<br />\nTwo<br />\n<br />\nThree<br />\n'

    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';

    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function str_pad (input, pad_length, pad_string, pad_type) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // + namespaced by: Michael White (http://getsprink.com)
    // +      input by: Marco van Oort
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: str_pad('Kevin van Zonneveld', 30, '-=', 'STR_PAD_LEFT');
    // *     returns 1: '-=-=-=-=-=-Kevin van Zonneveld'
    // *     example 2: str_pad('Kevin van Zonneveld', 30, '-', 'STR_PAD_BOTH');
    // *     returns 2: '------Kevin van Zonneveld-----'

    var half = '', pad_to_go;

    var str_pad_repeater = function (s, len) {
        var collect = '', i;

        while (collect.length < len) {collect += s;}
        collect = collect.substr(0,len);

        return collect;
    };

    input += '';
    pad_string = pad_string !== undefined ? pad_string : ' ';
    
    if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') { pad_type = 'STR_PAD_RIGHT'; }
    if ((pad_to_go = pad_length - input.length) > 0) {
        if (pad_type == 'STR_PAD_LEFT') { input = str_pad_repeater(pad_string, pad_to_go) + input; }
        else if (pad_type == 'STR_PAD_RIGHT') { input = input + str_pad_repeater(pad_string, pad_to_go); }
        else if (pad_type == 'STR_PAD_BOTH') {
            half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
            input = half + input + half;
            input = input.substr(0, pad_length);
        }
    }

    return input;
}



function largeurFenetre() {
var viewportwidth;
 var viewportheight;
 
 // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
 
 if (typeof window.innerWidth != 'undefined')
 {
      viewportwidth = window.innerWidth,
      viewportheight = window.innerHeight
 }
 
// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)

 else if (typeof document.documentElement != 'undefined'
     && typeof document.documentElement.clientWidth !=
     'undefined' && document.documentElement.clientWidth != 0)
 {
       viewportwidth = document.documentElement.clientWidth,
       viewportheight = document.documentElement.clientHeight
 }
 
 // older versions of IE
 
 else
 {
       viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
       viewportheight = document.getElementsByTagName('body')[0].clientHeight
 }
	return viewportwidth;
}