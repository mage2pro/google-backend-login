<?php
namespace Dfe\GoogleBackendLogin\Plugin\Backend\App;
use Df\Api\Settings\Google;
use Magento\Backend\App\AbstractAction as Sb;
use Magento\Framework\App\RequestInterface;
class AbstractAction {
	/**
	 * 2015-07-23
	 * Цель метода — авторизация в административной части посредством OAuth.
	 * Обратите внимание, что в ядре Magento уже есть плагин для данного класса:
	 * app/code/Magento/Backend/etc/adminhtml/di.xml
	 * Он выполняет стандартную авторизацию в административной части и имеет вес 100.
	 * Наш же имеет вес 99 и выполняется раньше стандартного.
	 * @param Sb $sb
	 * @param RequestInterface $request
	 * @return void
	 */
	public function beforeDispatch(Sb $sb, RequestInterface $request) {
		/** @var bool|null $isOAuthLogin */
		$isOAuthLogin = $request->getParam('dfe-google-login');
		/** @var string|null $token */
		$token = $request->getParam('id_token');
		if ($isOAuthLogin && !df_backend_auth()->isLoggedIn() && $token) {
			/** https://developers.google.com/identity/sign-in/web/backend-auth */
			/** @var string $json */
			/**
			 * 2015-11-27
			 * Обратите внимание, что для использования @uses file_get_contents
			 * с адресами https требуется расширение php_openssl интерпретатора PHP,
			 * однако оно является системным требованием Magento 2:
			 * http://devdocs.magento.com/guides/v2.0/install-gde/system-requirements.html#required-php-extensions
			 * Поэтому мы вправе использовать здесь @uses file_get_contents
			 */
			$json = df_http_get('https://www.googleapis.com/oauth2/v3/tokeninfo', [
				'id_token' => $token
			]);
			if ($json) {
				$googleResponse = df_json_decode($json);
				if ($googleResponse) {
					/** @var string $email */
					$email = dfa($googleResponse, 'email');
					/**
					 * The value of aud in the ID token is equal to one of your app's client IDs.
					 * This check is necessary to prevent ID tokens issued to a malicious app
					 * being used to access data about the same user on your app's backend server.
					 *
					 * Говоря простым языком, нам надо убедиться,
					 * что админгистратор авторизован именно в нашем приложении, а не в каком-то ещё.
					 * https://developers.google.com/identity/sign-in/web/backend-auth#verify-the-integrity-of-the-id-token
					 */
					/** @var string $clientId */
					$clientId = dfa($googleResponse, 'aud');
					/** @var string $expectedClientId */
					$expectedClientId = Google::s()->clientId();
					if ($email && $clientId === $expectedClientId) {
						df_backend_auth()->loginByEmail($email);
					}
				}
			}
		}
	}
}