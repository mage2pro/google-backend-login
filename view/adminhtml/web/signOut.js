require(['jquery', 'jquery/jquery.cookie'], function($) {
	if (1 == $.cookie('dfe-logged-with-google')) {
		console.log('logged with google');
		console.log(document.cookie);
		window.dfeGoogleOnLoad = function() {
			console.log(document.cookie);
			$(function() {
				if (!$('#dfeGoogleSignIn').length) {
					console.log('sign out logic...');
					gapi.load('auth2', function() {
						//debugger;
						var auth2 = gapi.auth2.init({
							'client_id': $('meta[name=google-signin-client_id]').attr('content')
						});
						auth2.then(function() {
							console.log('is signed in: ' + auth2.isSignedIn.get());
							debugger;
							if (auth2.isSignedIn.get()) {
								$('a.account-signout').click(function(event) {
									event.preventDefault();
									console.log('sign out!');
									$.cookie('dfe-logged-with-google', 0, { path: '/'});
									if (1 == $.cookie('dfe-logged-with-google')) {
										console.log('COOKIE NOT REMOVED!!!');
										console.log($.cookie('dfe-logged-with-google'));
									}
									else {
										//debugger;
										var logoutUrl = $(this).attr('href');
										auth2.signOut().then(function() {
											window.location.href = logoutUrl;
										});
									}
								});
							}
							//auth2.signIn({scope: 'profile'}).then(function() {
								//console.log('signed in!');
								//debugger;
							//});
						});
					});
				}
			});
		};
		require(['https://apis.google.com/js/platform.js?onload=dfeGoogleOnLoad'], function() {});
	}
});

