export default function getNow(){
	//-# uncomment, specify date in URL to test dates
	dev: {
		var parm
		if(window.URLSearchParams && (parm = new URLSearchParams(location.search)) && parm.has('now')){
			var now = parm.get('now');
			if(now.length === 8){
				var y = now.substr(0, 4);
				var m = now.substr(4, 2) - 1;
				var d = now.substr(6, 2);
			}else{
				var y = (new Date()).getFullYear();
				var m = now.substr(0, 2) - 1;
				var d = now.substr(2, 2);
			}
			return new Date(y, m, d, 0, 0, 0)
		}
	}
	return new Date();
};
