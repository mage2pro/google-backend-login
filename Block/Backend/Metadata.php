<?php
namespace Dfe\Google\Block\Backend;
class Metadata extends \Magento\Backend\Block\AbstractBlock {
	/**
	 * @override
	 * @see \Magento\Backend\Block\AbstractBlock::_construct()
	 * @return void
	 */
	protected function _construct() {
		df_metadata('google-signin-client_id', \Dfe\Google\Settings\Login::s()->clientId());
	}
}