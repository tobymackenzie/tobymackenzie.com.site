import {baseCut as _cutsMustard} from '../ua/mustardCut.js';

var doc = document;
var body = doc.body;
var cutsMustard = _cutsMustard &&  body.showPopover;
function main(container){
	if(!container){
		container = body;
	}
	var els = container.querySelectorAll('pre:has(code)');
	els.forEach(function(el){
		el.setAttribute('data-breakover', '1');
		var toolbar = el.querySelector('.toolbar');
		if(!toolbar){
			toolbar = doc.createElement('div');
			toolbar.classList.add('toolbar');
			el.insertBefore(toolbar, el.firstChild);
		}
		var btn = doc.createElement('button');
		btn.classList.add('breakoverAct');
		btn.innerHTML = '⛶';
		btn.title = 'Expand';
		// ⌞⌝ 🔎
		btn.addEventListener('click', function(){
			if(!el.popover){
				el.setAttribute('popover', 'auto');
				el.showPopover();
				el.setAttribute('data-breakover', 'open');
			}else{
				el.hidePopover();
			}
		});
		el.addEventListener('toggle', function(e){
			if(e.newState === 'closed'){
				el.removeAttribute('popover');
				el.setAttribute('data-breakover', '1');
			}
		});
		toolbar.appendChild(btn);
	});

};
if(cutsMustard){
	if(doc.readyState !== 'loading'){
		main();
	}else{
		doc.addEventListener('DOMContentLoaded', main);
	}
}
export default cutsMustard ? main : function(){};
