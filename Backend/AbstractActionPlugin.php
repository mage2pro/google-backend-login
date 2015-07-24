<?php
namespace Dfe\Login\Backend;
class AbstractActionPlugin {
	/**
	 * @param \Df\Backend\Model\Auth $auth
	 */
	public function __construct(\Df\Backend\Model\Auth $auth) {
		$this->_auth = $auth;
	}

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
		rm_log(__METHOD__);
		rm_log('***********************************************************');
		rm_log(df_current_url());
		rm_log('***********************************************************');
		rm_log($_COOKIE);
		/** @var bool|null $isOAuthLogin */
		$isOAuthLogin = $request->getParam('df-login-google');
		/** @var string|null $token */
		$token = $request->getParam('token');
		rm_log('df-login-google: ' . intval($isOAuthLogin));
		if ($isOAuthLogin && $token && !$this->_auth->isLoggedIn()) {
			rm_log('trying login by Google...');
			rm_log('df-login-google: ' . intval($isOAuthLogin));
			/** @link https://developers.google.com/identity/sign-in/web/backend-auth */
			/** @var string $json */
			$json = file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token");
			rm_log($json);
			if ($json) {
				$googleResponse = json_decode($json, $assoc = true);
				if ($googleResponse) {
					/** @var string $email */
					$email = df_a($googleResponse, 'email');
					if ($email) {
						$this->_auth->loginByEmail($email);
					}
				}
			}
		}
		return array($request);
	}
}