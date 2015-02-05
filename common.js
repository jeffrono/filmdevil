var isMac = (navigator.userAgent.indexOf("Mac") != -1);

// Hides a box if it is being shown, else makes it visible
function showHide(boxName) {
  var obj = document.all(boxName);
  var display = obj.style.display == "none" ? "inline" : "none";
  obj.style.display = display;
}

// Returns true if option was found and selected, false otherwise
function setOption(selectBox, option) {
    for(i = 0; i < selectBox.options.length; i++) {
    	if(selectBox.options(i).value == option)
            break;
    }
    if(i == selectBox.options.length) {
    	//alert("Couldn't find option: " + option + " for select box " + selectBox.name);
        return false;
    }
    selectBox.selectedIndex = i;
    return true;
}

// Returns true if option was found and selected, false otherwise
function setRadio(radioName, option) {
		var length = document.all(radioName).length;
    for(i = 0; i < length; i++) {
    	if(document.all(radioName, i).value == option) {
    		document.all(radioName, i).click();
				return true;
			}
    }
    return false;
}

function validateURL(object,text) {
    if (object.value.length > 0){
     	rexp = /http(s?):\/\//;
		if(rexp.test(object.value))
   			return true;
		else {
        	alert(text + ' field must begin with http:// or https://');
        	if (navigator.appName.indexOf('Netscape') > -1) {
            	object.focus();
        	}
			return false;
		}
	}
	else { return true; }
}

function validatePassword() {
	if(document.form1.password1.value.length == 0
    	&& document.form1.password2.value.length == 0)
        return true;
    if(document.form1.password1.value != document.form1.password2.value) {
    	alert("Sorry, the two passwords don't match");
        return false;
    }
    if(document.form1.password1.value.length < 4) {
    	alert("Sorry, the password has to be 4 or more letters, numbers, and symbols");
        return false;
    }
    return true;
}

function inSearchFrameset() {
	return window.top.frames("mainFrame").location.pathname.indexOf("searchFrameset.php")
		> -1;
}
