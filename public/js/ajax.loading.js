/*
 Debra branch management tool
 (C) Copyright 2015 fashion4home GmbH <www.fashionforhome.de>, Author Eduard Bess <eduard.bess@fashion4home.de>

 license GPL-3.0
*/
$(function(){
	$('#empty-session-cache').click(function(){

		loadingSpinner('show');

		var dest = $(this);
		$.ajax({
			url: dest.attr('data-request-url'),
			method: 'GET'
		}).success(function(){
			window.location = dest.attr('data-redirect-url');
		});

	});

	$('#login').click(function(){

		loadingSpinner('show');

		var dest = $(this);
		$.ajax({
			url: dest.attr('data-request-url'),
			method: 'POST',
			data: {
				username: $('#login-username').val(),
				password: $('#login-password').val()
			}
		}).success(function(){
			window.location = dest.attr('data-redirect-url');
		});

	});
});