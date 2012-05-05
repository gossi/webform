$.webshims.debug = true;
$.webshims.setOptions({
	extendNative: false,
	'forms-ext': {
		calculateWidth: false,
		datepicker: {
			dateFormat: 'yy-mm-dd',
			constrainInput: true,
			changeMonth: true,
			changeYear: true,
			showWeek: false
		}
	}
});
$.webshims.polyfill("forms forms-ext");

// feature detect pointer events
document.addEventListener("DOMContentLoaded", function () {
	var dummy = document.createElement('_'),
		supported = false;
	
	if ('pointerEvents' in dummy.style) {
		dummy.style.pointerEvents = 'auto';
		dummy.style.pointerEvents = 'x';
		document.body.appendChild(dummy);
		supported = getComputedStyle(dummy).pointerEvents === 'auto';
		document.body.removeChild(dummy);
	}

	if (supported) {
		document.documentElement.classList.add("pointer-events-support");
	}
});

var webform = (function () {
	var composites = [];
	
	var testMatches = function (message, controls) {
		var ok = true, val = null;
		if (controls.length) {
			val = document.getElementById(controls[0]).value;

			// first check if all controls are matching...
			controls.forEach(function (id) {
				ok = ok && val === document.getElementById(id).value;
			});

			// ... and set the validity message
			controls.forEach(function (id) {
				document.getElementById(id).setCustomValidity(ok ? null : message);
			});	
		}
		return ok;
	};
	
	return {
		addMatchTest : function (message, controls) {
			controls.forEach(function (id) {
				document.getElementById(id).addEventListener("keyup", function() {
					testMatches(message, controls);
				}, false);
			});
		},

		addCompositeControl : function (controlId) {
			composites.push(controlId);
		},
		
		setup : function () {
			// composites
			composites.forEach(function (controlId) {
				var control = document.getElementById(controlId),
					toggle = function(e) {
						e.target.parentNode.classList.toggle("focus");
					};
		
				// move on to next control, if replaced by webshims datepicker
//				console.log("Next sibling: " + control.name + " shadow: " + ($(control).getShadowElement() === control) + " next sib: " + control.nextSibling + " hasDatepicker: " + (control.nextSibling && control.nextSibling.classList.contains("hasDatepicker")));
				if (/*control.type === "date" && */control.nextSibling 
						&& control.nextSibling.classList.contains("hasDatepicker")) {
					
					control = control.nextSibling;
				}
		
				control.addEventListener("focus", toggle);
				control.addEventListener("blur", toggle);
			});
		}
	};
})();

// how to know when webshims are poly-filled?
$.webshims.ready("DOM forms forms-ext", function() {
	window.setTimeout(function() {
		webform.setup();
	}, 100);
});