/*        function adjustLogo(etat) {
        	if(etat) {
                	document.getElementById('float_logo').style.top=window.offsetHeight = window.scrollTop;
        	}
        	setTimeout('adjustLogo(true);',100);

        }

    var dragsort = ToolMan.dragsort()
    var junkdrawer = ToolMan.junkdrawer()
        window.onload = function() {
//			adjustLogo();

			if(document.getElementById('boxes')) {
	                junkdrawer.restoreListOrder("boxes");
        	        dragsort.makeListSortable(document.getElementById("boxes"),saveOrder);
				}
        }
 
 
        function saveOrder(item) {
                var group = item.toolManDragGroup
                var list = group.element.parentNode
                var id = list.getAttribute("id")
 
                if (id == null) return
                group.register('dragend', function() {
                        lis = list.getElementsByTagName('li');
                        liste='';
                        for(i=0;i<lis.length;i++){
                                liste+=lis[i].getAttribute('file')+'|';
                        }
                        g('images_order').value=liste;
                })
        }

*/