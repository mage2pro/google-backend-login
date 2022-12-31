<?php
namespace Dfe\GoogleBackendLogin\Plugin\Backend\App;
use Df\Google\Settings as S;
use Magento\Backend\App\AbstractAction as Sb;
use Magento\Framework\App\RequestInterface;
class AbstractAction {
	/**
	 * 2015-07-23
	 * Цель метода — авторизация в административной части посредством OAuth.
	 * Обратите внимание, что в ядре Magento уже есть плагин для данного класса:
	 * app/code/Magento/Backend/etc/adminhtml/di.xml
	 *	https://github.com/magento/magento2/blob/052e789/app/code/Magento/Backend/etc/adminhtml/di.xml#L64-L67
	 *	<type name="Magento\Backend\App\AbstractAction">
	 *		<plugin name="adminAuthentication" type="Magento\Backend\App\Action\Plugin\Authentication" sortOrder="100" />
	 *		<plugin name="adminMassactionKey" type="Magento\Backend\App\Action\Plugin\MassactionKey" sortOrder="11" />
	 *	</type>
	 * Он выполняет стандартную авторизацию в административной части и имеет вес 100.
	 * Наш же имеет вес 99 и выполняется раньше стандартного.
	 * @see \Magento\Backend\App\AbstractAction::dispatch()
	 */
	function beforeDispatch(Sb $sb, RequestInterface $request):void {
		$isOAuthLogin = $request->getParam('dfe-google-login'); /** @var bool|null $isOAuthLogin */
		$token = $request->getParam('id_token'); /** @var string|null $token */
		if ($isOAuthLogin && !df_backend_auth()->isLoggedIn() && $token) {
			/** https://developers.google.com/identity/sign-in/web/backend-auth */
			/** @var array(string => string) $res */
			if ($res = df_http_json('https://www.googleapis.com/oauth2/v3/tokeninfo', ['id_token' => $token], 0, '')) {
				$email = dfa($res, 'email'); /** @var string $email */
				# 1) The value of aud in the ID token is equal to one of your app's client IDs.
				# This check is necessary to prevent ID tokens issued to a malicious app
				# being used to access data about the same user on your app's backend server.
				# 2) Говоря простым языком, нам надо убедиться,
				# что администратор авторизован именно в нашем приложении, а не в каком-то ещё.
				# https://developers.google.com/identity/sign-in/web/backend-auth#verify-the-integrity-of-the-id-token
				$clientId = dfa($res, 'aud'); /** @var string $clientId */
				$expectedClientId = S::s()->clientId(); /** @var string $expectedClientId */
				if ($email && $clientId === $expectedClientId) {
					df_backend_auth()->loginByEmail($email);
				}
			}
		}
	}
}