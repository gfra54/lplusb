$(document).ready(function() {
	$(".fancybox").fancybox();

    $( "#boxes" ).sortable({'stop':function(){}});

    $( "#sortable" ).sortable({'stop':function(){
		var ids = '';

		if($("#sortable input:checkbox").length) {
			$("#sortable input:checkbox").each(function() {
				ids+=ids ? ',' : '';
				ids+=$(this).val();
			});
	    	$.post(document.location.href,{
	    		w:'sort',
	    		ids : ids
	    	}).done(function(data){
	    		console.log(data)
	    	});
	    }
    }});
    $( "#sortable" ).disableSelection();

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