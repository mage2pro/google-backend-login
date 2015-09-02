<?php
namespace Dfe\Login\Backend;
class OAuth {
	/**
	 * @param \Df\Backend\Model\Auth $auth
	 */
	public function __construct(\Df\Backend\Model\Auth $auth) {$this->_auth = $auth;}

	/**
	 * 2015-07-23
	 * Цель метода —
	 * авторизация в административной части посредством OAuth.
	 * Обратите внимание, что в ядре Magento уже есть плагин для данного класса:
	 * app/code/Magento/Backend/etc/adminhtml/di.xml
	 * Он выполняет стандартную авторизацию в административной части и имеет вес 100.
	 * Наш же имеет вес 99 и выполняется раньше стандартного.
	 * @param \Magento\Backend\App\AbstractAction $subject
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return \Magento\Framework\App\RequestInterface $request
	 */
	public function beforeDispatch(
		\Magento\Backend\App\AbstractAction $subject
		,\Magento\Framework\App\RequestInterface $request
	) {
		/** @var bool|null $isOAuthLogin */
		$isOAuthLogin = $request->getParam('dfe-login-google');
		/** @var string|null $token */
		$token = $request->getParam('id_token');
		if ($isOAuthLogin && !$this->_auth->isLoggedIn() && $token) {
			/** https://developers.google.com/identity/sign-in/web/backend-auth */
			/** @var string $json */
			$json = file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token");
			if ($json) {
				$googleResponse = json_decode($json, $assoc = true);
				if ($googleResponse) {
					/** @var string $email */
					$email = df_a($googleResponse, 'email');
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
					$clientId = df_a($googleResponse, 'aud');
					$expectedClientId = \Dfe\Login\Settings\Google::s()->clientId();
					if ($email && $clientId === $expectedClientId) {
						$this->_auth->loginByEmail($email);
					}
				}
			}
		}
		return [$request];
	}
}