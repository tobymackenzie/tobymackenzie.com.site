export default function onnextday(cb){
	var then = new Date();
	then.setHours(24, 0, 0, 0);
	var diff = then - (new Date());
	if(diff <= 100){ diff = 360000 };
	return setTimeout(function(){ cb(); onnextday(cb); }, diff);
};
