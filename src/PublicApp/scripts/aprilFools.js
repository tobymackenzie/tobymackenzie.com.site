var doAnimation = !(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches);
if(doAnimation){
	var $el = document.querySelector('html');
	var original = {
		position: $el.style.position,
		transform: $el.style.transform,
		transformOrigin: $el.style.transformOrigin,
	};
	$el.style.transition += ' transform 0.5s ease';
	$el.style.position = 'relative';
	$el.style.transform = 'scaleX(-1)';
	$el.style.transformOrigin = 'center';
	var done = function(){
		$el.style.transform = 'scaleX(1)';
		document.removeEventListener('click', done);
		document.removeEventListener('mousemove', done);
		console.log('april fools');
		$el.style.position = original.position;
		$el.style.transform = original.transform;
		$el.style.transformOrigin = original.transformOrigin;
	};
	document.addEventListener('click', done);
	document.addEventListener('mousemove', done);
}
