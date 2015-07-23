<?php
namespace Df\Login\Backend;
class AbstractActionPlugin {
	/**
	 * @param \Magento\Backend\Model\Auth $auth
	 * @param \Magento\Framework\Data\Collection\ModelFactory $modelFactory
	 */
	public function __construct(
		\Magento\Backend\Model\Auth $auth
		,\Magento\Framework\Data\Collection\ModelFactory $modelFactory
	) {
		$this->_auth = $auth;
		$this->_modelFactory = $modelFactory;
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
		/** @var bool|null $isOAuthLogin */
		$isOAuthLogin = $request->getParam('df-login-google');
		/** @var string|null $token */
		$token = $request->getParam('token');
		if ($isOAuthLogin && $token && !$this->_auth->isLoggedIn()) {
			/** @link https://developers.google.com/identity/sign-in/web/backend-auth */
			/** @var string $json */
			$json = file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token");
			if ($json) {
				$googleResponse = json_decode($json, $assoc = true);
				if ($googleResponse) {
					/** @var string $email */
					$email = df_a($googleResponse, 'email');
					if ($email) {
					}
				}
			}
		}
		rm_log(__METHOD__);
		return array($request);
	}
}