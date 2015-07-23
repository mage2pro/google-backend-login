<?php
namespace Df\Login\Controller\Adminhtml\DfLogin;
class Google extends \Magento\Backend\App\AbstractAction {
	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 */
	public function __construct(\Magento\Backend\App\Action\Context $context) {
		rm_log(__METHOD__);
		parent::__construct($context);
	}

	/**
	 * @return void
	 */
	public function execute() {
		rm_log(__METHOD__);
		df_bt();
	}

	/**
	 * @override
	 * @return bool
	 */
	protected function _isAllowed() {return true;}
}
