<?php
namespace Dfe\GoogleBackendLogin\Block;
class Metadata extends \Magento\Backend\Block\AbstractBlock {
	/**
	 * @override
	 * @see \Magento\Backend\Block\AbstractBlock::_construct()
	 * @return void
	 */
	protected function _construct() {
		parent::_construct();
		df_metadata('google-signin-client_id', \Df\Api\Settings\Google::s()->clientId());
	}
}