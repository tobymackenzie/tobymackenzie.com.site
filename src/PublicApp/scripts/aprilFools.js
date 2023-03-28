if(!(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches)){
	var doc = document;
	var $el = doc.querySelector('html');
	var style = $el.style;
	var orgPosition = style.position;
	var orgTransform = style.transform;
	var orgTransformOrigin = style.transformOrigin;
	style.transition += ' transform 0.5s ease';
	style.position = 'relative';
	style.transform = 'scaleX(-1)';
	style.transformOrigin = 'center';
	var done = function(){
		style.transform = 'scaleX(1)';
		doc.removeEventListener('click', done);
		doc.removeEventListener('mousemove', done);
		console.log('april fools');
		style.position = orgPosition;
		style.transform = orgTransform;
		style.transformOrigin = orgTransformOrigin;
	};
	doc.addEventListener('click', done);
	doc.addEventListener('mousemove', done);
}
