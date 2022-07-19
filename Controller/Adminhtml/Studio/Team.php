<?php

namespace Dotdigitalgroup\Chat\Controller\Adminhtml\Studio;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Team extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Dotdigitalgroup_Email::iframe';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute method.
     *
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->getConfig()
            ->getTitle()
            ->prepend(__('Chat Team / Widget'));
        return $resultPage;
    }
}
