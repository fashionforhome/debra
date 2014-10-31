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