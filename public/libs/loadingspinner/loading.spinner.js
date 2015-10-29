/*
 Debra branch management tool
 (C) Copyright 2015 fashion4home GmbH <www.fashionforhome.de>, Author Eduard Bess <eduard.bess@fashion4home.de>

 license GPL-3.0
 */
function loadingSpinner(type)
{
	if (type == 'show') {
		$('body').prepend('<div id="loading-overlay"><div></div></div>')
	} else if (type == 'hide') {
		$('#loading-overlay').remove();
	}

}