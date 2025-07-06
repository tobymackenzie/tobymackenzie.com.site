export default function onnextday(cb){
	cb();
	var then = new Date();
	then.setHours(24, 0, 0, 0);
	var diff = then - (new Date());
	if(diff <= 100){ diff = 360000 };
	setTimeout(function(){ onday(cb) }, diff);
};
