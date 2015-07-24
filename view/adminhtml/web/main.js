require([
	'jquery'
	, 'jquery/jquery.cookie'
	, 'https://apis.google.com/js/platform.js'
], function($) {$(function() {
	/**
	 * @link https://developers.google.com/identity/sign-in/web/reference#gapisignin2renderwzxhzdk114idwzxhzdk115_wzxhzdk116optionswzxhzdk117
	 */
	//return;
	console.log('before dfeGoogleSignIn');
	console.log(document.cookie);
	gapi.signin2.render('dfeGoogleSignIn', {
		//'scope': 'https://www.googleapis.com/auth/plus.login',
		'scope': 'profile'
		,'width': 110
		,'height': 45
		,'longtitle': false
		,'theme': 'light'
		,'onsuccess': function(user) {
			console.log('onsuccess');
			console.log(user.getBasicProfile().getEmail());
			console.log(document.cookie);
			/**
			 * Дальше надо авторизоваться в админке:
			 * https://developers.google.com/identity/sign-in/web/backend-auth
			 */
			//console.log('страница обновилась');
			//return;
			$.post(BASE_URL + 'dfeLogin/google/', {
				token: user.getAuthResponse().id_token
				//, form_key: FORM_KEY
				, 'df-login-google': 1
			}, function(response) {
				if (response.success) {
					console.log('авторизован :-)');
					console.log(document.cookie);
					debugger;
					$.cookie('dfe-logged-with-google', '1', {path: '/'});
					window.location.href = BASE_URL;
				}
				else {
					console.log('не в этот раз...');
					debugger;
					//window.location.href = BASE_URL;
				}
			});
		}
		,'onfailure': function() {}
	});
});});
