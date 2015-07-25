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
		parent::__construct($context);
		$this->resultJsonFactory = $resultJsonFactory;
	}

	/**
	 * @return \Magento\Framework\Controller\Result\Json
	 */
	public function execute() {
		/** @var \Magento\Framework\Controller\Result\Json $resultJson */
		$resultJson = $this->resultJsonFactory->create();
		/**
		 * 2015-07-25
		 * Надо проверить, что пользователь (или взломщик?) ничего не подмухлевал.
		 * @link https://developers.google.com/identity/sign-in/web/backend-auth#verify-the-integrity-of-the-id-token
		 */
		return $resultJson->setData(['success' => true]);
	}

	/**
	 * @override
	 * @return bool
	 */
	protected function _isAllowed() {return true;}
}
