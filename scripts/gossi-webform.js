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