<?php
namespace Dfe\Google\Block\Backend;
class Metadata extends \Magento\Backend\Block\AbstractBlock {
	/**
	 * @param \Magento\Backend\Block\Context $context
	 * @param \Magento\Framework\View\Page\Config $pageConfig
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
	 */
	public function __construct(
		\Magento\Backend\Block\Context $context
		,\Magento\Framework\View\Page\Config $pageConfig
		,\Magento\Framework\App\Config\ScopeConfigInterface $config
	) {
		$pageConfig->setMetadata('google-signin-client_id', \Dfe\Google\Settings\Login::s()->clientId());
		parent::__construct($context);
	}
}