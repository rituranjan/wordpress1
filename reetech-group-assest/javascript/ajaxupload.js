function $m(theVar){
	return document.getElementById(theVar)
}
function remove(theVar){
	var theParent = theVar.parentNode;
	theParent.removeChild(theVar);
}
function addEvent(obj, evType, fn){
	if(obj.addEventListener)
	    obj.addEventListener(evType, fn, true)
	if(obj.attachEvent)
	    obj.attachEvent("on"+evType, fn)
}
function removeEvent(obj, type, fn){
	if(obj.detachEvent){
		obj.detachEvent('on'+type, fn);
	}else{
		obj.removeEventListener(type, fn, false);
	}
}
function isOlderSafari(){
    
	const UA = navigator.userAgent;
    console.log(UA)
    const regexp = RegExp('Version', 'i'); // as far as I can see, now only 'Version' in the useragent is special for the Safari browser.
    const isSafari = regexp.test(UA) ? true : false;

    if (isSafari) { 
        const browserVersion = UA.match(/Version\/(\d*).(\d*).(\d*)/);
        console.log(browserVersion);
        // checking if older than version 13.1.2 as newer versions needs a wait before removing ajax-temp, like non-Safari browsers
        if (browserVersion[1] > 13) {
            return false;
        } else if (browserVersion[1] == 13 && browserVersion[2] > 1) {
            return false;
        } else if (browserVersion[1] == 13 && browserVersion[2] == 1 && browserVersion[3] >= 2) {
            return false;
        } else {
            return true;
        }
    } else {

        return false;
    }

}
function ajaxUpload(form,url_action,id_element,html_show_loading,html_error_http){
	var detectWebKit = isOlderSafari();
    console.log(detectWebKit);
	form = typeof(form)=="string"?$m(form):form;
	var erro="";
	if(form==null || typeof(form)=="undefined"){
		erro += "The form of 1st parameter does not exists.\n";
	}else if(form.nodeName.toLowerCase()!="form"){
		erro += "The form of 1st parameter its not a form.\n";
	}
	if($m(id_element)==null){
		erro += "The element of 3rd parameter does not exists.\n";
	}
	if(erro.length>0){
		alert("Error in call ajaxUpload:\n" + erro);
		return;
	}
	var iframe = document.createElement("iframe");
	iframe.setAttribute("id","ajax-temp");
	iframe.setAttribute("name","ajax-temp");
	iframe.setAttribute("width","0");
	iframe.setAttribute("height","0");
	iframe.setAttribute("border","0");
	iframe.setAttribute("style","width: 0; height: 0; border: none;");
	form.parentNode.appendChild(iframe);
	window.frames['ajax-temp'].name="ajax-temp";
	var doUpload = function(){
		removeEvent($m('ajax-temp'),"load", doUpload);
		var cross = "javascript: ";
		cross += "window.parent.$m('"+id_element+"').innerHTML = document.body.innerHTML; void(0);";
		$m(id_element).innerHTML = html_error_http;
		$m('ajax-temp').src = cross;
        if(detectWebKit){
            remove($m('ajax-temp'));
	    }else{
        	setTimeout(function(){ remove($m('ajax-temp'))}, 250);
        }
    }
	addEvent($m('ajax-temp'),"load", function() {
		if (this.contentDocument && this.contentDocument.body.innerHTML.indexOf('9b8b5089-0981-45ed-867f-de3800d9dd69') != -1)
			doUpload();
		else
			$m(id_element).innerHTML = html_error_http;
	});
	form.setAttribute("target","ajax-temp");
	form.setAttribute("action",url_action);
	form.setAttribute("method","post");
	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("encoding","multipart/form-data");
	if(html_show_loading.length > 0){
		$m(id_element).innerHTML = html_show_loading;
	}
	document.forms["invoice_form"].submit();

}