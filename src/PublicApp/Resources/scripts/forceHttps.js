if('location' in window && location.protocol === 'http:'){
	location.replace('https' + location.href.slice(4));
}
