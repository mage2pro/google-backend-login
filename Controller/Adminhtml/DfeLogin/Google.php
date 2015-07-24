<?php
namespace Dfe\Login\Controller\Adminhtml\DfeLogin;
class Google extends \Magento\Backend\App\AbstractAction {
	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context
		,\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	) {
		rm_log(__METHOD__);
		parent::__construct($context);
		$this->resultJsonFactory = $resultJsonFactory;
	}

	/**
	 * @return \Magento\Framework\Controller\Result\Json
	 */
	public function execute() {
		rm_log(__METHOD__);
		/** @var \Magento\Framework\Controller\Result\Json $resultJson */
		$resultJson = $this->resultJsonFactory->create();
		return $resultJson->setData(['success' => true]);
	}

	/**
	 * @override
	 * @return bool
	 */
	protected function _isAllowed() {return true;}
}
