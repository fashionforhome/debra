function loadingSpinner(type)
{
	if (type == 'show') {
		$('body').prepend('<div id="loading-overlay"><div></div></div>')
	} else if (type == 'hide') {
		$('#loading-overlay').remove();
	}

}