<?php
namespace Dfe\GoogleBackendLogin\Block;
use Df\Google\Settings as S;
class Metadata extends \Magento\Backend\Block\AbstractBlock {
	/**
	 * @override
	 * @see \Magento\Backend\Block\AbstractBlock::_construct()
	 */
	protected function _construct() {
		parent::_construct();
		df_metadata('google-signin-client_id', S::s()->clientId());
	}
}