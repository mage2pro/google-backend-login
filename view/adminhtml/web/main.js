require(['jquery'], function($) {
	$(function() {
		/**
		 * @link https://developers.google.com/identity/sign-in/web/reference#gapisignin2renderwzxhzdk114idwzxhzdk115_wzxhzdk116optionswzxhzdk117
		 */
		gapi.signin2.render('dfGoogleSignIn', {
			//'scope': 'https://www.googleapis.com/auth/plus.login',
			'scope': 'profile'
			,'width': 110
			,'height': 45
			,'longtitle': false
			,'theme': 'light'
			,'onsuccess': function(user) {
				console.log(user.getBasicProfile().getEmail());
				/**
				 * Дальше надо авторизоваться в админке:
				 * https://developers.google.com/identity/sign-in/web/backend-auth
				 */
				//console.log('страница обновилась');
				$.post(BASE_URL + 'dfLogin/google/', {
					token: user.getAuthResponse().id_token
					, form_key: FORM_KEY
					, 'df-login-google': true
				}, function(response) {
					if (response.success) {
						console.log('авторизован :-)');
						//window.location.href = BASE_URL;
					}
					else {
						console.log('не в этот раз...');
					}
				});
			}
			,'onfailure': function() {}
		});
	});
});
