console.log('april fools');
var $el = document.querySelector('html');
var rotated = true;
$el.style.position = 'relative';
$el.style.transition += ' transform 0.5s ease';
$el.style.transformOrigin = 'center';
$el.style.transform += ' rotate(360deg)';

document.addEventListener('click', function(){
	console.log('april fools');
	$el.style.transform = $el.style.transform.replace(/rotate\([\w]+\)/, 'rotate(' + (rotated ? '0' : '360') + 'deg)');
	rotated = !rotated;
});
