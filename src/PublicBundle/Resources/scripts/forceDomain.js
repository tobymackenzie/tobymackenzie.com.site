if('location' in window && 'currentScript' in document && document.currentScript.getAttribute){
	var desiredLocation = document.currentScript.getAttribute('src').match(/([\w\-\+]+):\/\/([\w\.\-:]+)\//);
	if(location.host !== desiredLocation[2]){
		location.replace(desiredLocation[1] + '://' + desiredLocation[2] + location.pathname + location.search + ('hash' in location ? location.hash : ''));
	}
}
