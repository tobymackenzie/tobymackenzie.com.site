//--element.addEventListener
export function addListener(_elm, _name, _cb, _capt){
	return _elm.addEventListener(_name, _cb, _capt || false/*-# ff6- */);
};
