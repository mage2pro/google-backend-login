<?php
namespace Df\Login\Block\Backend;
class Buttons extends \Magento\Backend\Block\Template {
	/**
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Framework\View\Page\Config $pageConfig
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
	 */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context
		,\Magento\Framework\View\Page\Config $pageConfig
		,\Magento\Framework\App\Config\ScopeConfigInterface $config
	) {
		$pageConfig->setMetadata('google-signin-client_id',
			$config->getValue('df_login/google/client_id')
		);
		parent::__construct($context);
	}
}