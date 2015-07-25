require([
	'jquery'
	, 'jquery/jquery.cookie'
], function($) {$(function() {
	var cookie = {
		NAME: 'dfe-logged-with-google'
		,is: function() {return 1 == $.cookie(cookie.NAME);}
		,off: function() {$.cookie(cookie.NAME, '0', {path: '/'})}
		,on: function() {$.cookie(cookie.NAME, '1', {path: '/'})}
	};
	/** @type {String} */
	var SIGN_IN_BUTTON_ID = 'dfeGoogleSignIn';
	/** @type {HTMLDivElement} */
	var signInButton = document.getElementById(SIGN_IN_BUTTON_ID);
	/** @type {String} */
	var clientId = $('meta[name=google-signin-client_id]').attr('content');
	// Сразу проверяем наличие clientId,
	// потому что без него затевать дальнейшую байду бессмысленно.
	if (clientId && (signInButton || cookie.is())) {
		require(['https://apis.google.com/js/platform.js'], function() {
			if (signInButton) {
				// Экран авторизации
				gapi.signin2.render(SIGN_IN_BUTTON_ID, {
					'scope': 'profile'
					,'width': 110
					,'height': 45
					,'longtitle': false
					,'theme': 'light'
					,'onsuccess': function(user) {
						/**
						 * Дальше надо авторизоваться в админке
						 * и проверить, что пользователь (или взломщик?) ничего не подмухлевал:
						 * https://developers.google.com/identity/sign-in/web/backend-auth
						 * 2015-07-25
						 * Параметр form_key передавать необязательно,
						 * потому что ядро добавляет его автоматически:
						 * @link https://github.com/magento/magento2/blob/1.0.0-beta/lib/web/mage/backend/bootstrap.js#L43
						 */
						$.post(BASE_URL + 'dfeLogin/google/', {
							token: user.getAuthResponse().id_token, 'df-login-google': 1
						}, function(response) {
							if (response.success) {
								cookie.on();
								window.location.href = BASE_URL;
							}
						});
					}
				});
			}
			else {
				// Остальные экраны административной части
				gapi.load('auth2', function() {
					var auth2 = gapi.auth2.init({'client_id': clientId});
					auth2.then(function() {
						if (auth2.isSignedIn.get()) {
							$('a.account-signout').click(function(event) {
								event.preventDefault();
								cookie.off();
								var logoutUrl = $(this).attr('href');
								auth2.signOut().then(function() {window.location.href = logoutUrl;});
							});
						}
					});
				});
			}
		});
	}
})();});