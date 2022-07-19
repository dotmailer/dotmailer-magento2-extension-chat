<?php

namespace Dotdigitalgroup\Chat\Controller\Adminhtml\Studio;

use Dotdigitalgroup\Chat\Model\Config;
use Dotdigitalgroup\Email\Helper\Data;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Dotdigitalgroup_Chat::config';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var Config
     */
    private $config;

    /**
     * Index constructor.
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     * @param Config $config
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Data $helper,
        Config $config
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Execute method.
     *
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     * @throws LocalizedException
     */
    public function execute()
    {
        if ($this->helper->isEnabled() && !$this->config->isChatEnabled()) {
            return $this->resultRedirectFactory
                ->create()
                ->setPath('adminhtml/system_config/edit/section/chat_api_credentials');
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->getConfig()
            ->getTitle()
            ->prepend(__('Chat Studio'));
        return $resultPage;
    }
}
