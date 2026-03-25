//--element.addEventListener
export function addListener(_elm, _name, _cb, _capt){
	return _elm.addEventListener(_name, _cb, _capt || false/*-# ff6- */);
};
export function removeListener(_elm, _name, _cb, _capt){
	return _elm.removeEventListener(_name, _cb, _capt || false/*-# ff6- */);
};
