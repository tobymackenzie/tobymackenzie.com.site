import onnextday from './onnextday.js';

export default function onday(cb){
	cb();
	return onnextday(cb);
};

