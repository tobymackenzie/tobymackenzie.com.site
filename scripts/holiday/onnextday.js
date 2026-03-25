import getNow from './getNow.js';
export default function onnextday(cb){
	var then = getNow();
	then.setHours(24, 0, 0, 0);
	var diff = then - getNow();
	if(diff <= 100){ diff = 360000 };
	return setTimeout(function(){ cb(); onnextday(cb); }, diff);
};
