//-@ https://stackoverflow.com/a/7053197/1139122
export function ready(_run){
	if(document.readyState !== 'loading'){
		_run();
	}else if(document.addEventListener){
		document.addEventListener('DOMContentLoaded', _run);
	}else if(document.attachEvent){
		document.attachEvent('onreadystatechange', function(){
			if(document.readyState === 'complete'){
				_run();
			}
		})
	}
};
