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
				var loginScenario = function() {
					gapi.signin2.render(SIGN_IN_BUTTON_ID, {
						'scope': 'profile'
						,'width': 110
						,'height': 45
						,'longtitle': false
						,'theme': 'light'
						,'onsuccess': function(user) {
							cookie.on();
							var $form = $('<form/>').attr({
								action: window.location.href
								,method: 'post'
							});
							var addFields = function(fields) {
								for (var name in fields) {
									$form.append($('<input/>').attr({
										type: 'hidden', name: name, value: fields[name]
									}));
								}
							};
							addFields($.extend(user.getAuthResponse(), {
								form_key: FORM_KEY, 'dfe-login-google': 1
							}));
							$('body').append($form);
							$form.submit();
						}
					});
				};
				// Экран авторизации
				if (!cookie.is()) {
					loginScenario();
				}
				else {
					/**
					 * 2015-07-25
					 * Авторизация прошла в Google, но не прошла в Magento.
					 * Разовтаризовываем администратора в Google
					 * и даём ему возможность авторизоваться другим способом
					 * (в том числе, используя другую учётню запись Google).
					 */
					cookie.off();
					gapi.load('auth2', function() {
						var auth2 = gapi.auth2.init({'client_id': clientId});
						auth2.then(function() {
							if (auth2.isSignedIn.get()) {
								auth2.signOut().then(function() {loginScenario();});
							}
							else {
								// Вообще-то мы сюда не должны попадать,
								// но пусть будет, на всякий случай...
								loginScenario();
							}
						});
					});
				}
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
});});