<?php
namespace Dfe\Google\Settings;
class Login extends \Df\Core\Settings {
	/**
	 * @return string
	 */
	public function clientId() {return $this->v('client_id');}

	/**
	 * @override
	 * @used-by \Df\Core\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'dfe_google/login/';}

	/** @return \Dfe\Google\Settings\Login */
	public static function s() {static $r; return $r ? $r : $r = df_o(__CLASS__);}
}