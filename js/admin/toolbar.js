function alterTextSpecial(obj, q,o,f)
{
	if(val = prompt(q)) {
		wrapText(g(obj), '<'+(o.replace('0val0',val))+'>', f ? '</'+f+'>' : '');
		showPreview(obj,true);
		saveCaseLang(g(obj));
	}
}

function alterText(obj, tag)
{
	wrapText(g(obj), '<'+tag+'>', '</'+tag+'>');
	showPreview(obj,true);
	saveCaseLang(g(obj));
}

function wrapText(obj, beginTag, endTag)
{
	if(typeof obj.selectionStart == 'number')
	{
		// Mozilla, Opera, and other browsers
		var start = obj.selectionStart;
		var end   = obj.selectionEnd;
		
		obj.value = obj.value.substring(0, start) + beginTag + obj.value.substring(start, end) + endTag + obj.value.substring(end, obj.value.length);
	}
	else if(document.selection)
	{
		// Internet Explorer

		// make sure it's the textarea's selection
		obj.focus();
		var range = document.selection.createRange();
		if(range.parentElement() != obj) return false;

	    if(typeof range.text == 'string')
	        document.selection.createRange().text = beginTag + range.text + endTag;
	}
	else {
		_lib = confirm('Texte à mettre en forme');
		obj.value += beginTag+_lib+endTag
	}
;
		
};
