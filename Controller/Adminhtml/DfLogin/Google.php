<?php
namespace Df\Login\Controller\Adminhtml\DfLogin;
class Google extends \Magento\Backend\App\AbstractAction {
	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(\Magento\Backend\App\Action\Context $context) {
		\Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')
			->debug(__METHOD__)
		;
		parent::__construct($context);
	}

	/**
	 * @return void
	 */
	public function execute() {
		\Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')
			->debug(__METHOD__)
		;
	}

	/**
	 * @override
	 * @return bool
	 */
	protected function _isAllowed() {return true;}
}
