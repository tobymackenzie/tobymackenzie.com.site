if('location' in window && location.protocol === 'http:'){
	location.replace(location.href.replace('http', 'https'));
}
