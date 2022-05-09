<?php

namespace Dotdigitalgroup\Chat\Block\Adminhtml;

use Dotdigitalgroup\Chat\Model\Config;
use Dotdigitalgroup\Email\Block\Adminhtml\EngagementCloudTrialInterface;
use Dotdigitalgroup\Email\Block\Adminhtml\HandlesMicrositeRequests;
use Dotdigitalgroup\Email\Helper\Data;
use Dotdigitalgroup\Email\Helper\OauthValidator;
use Dotdigitalgroup\Email\Model\Integration\IntegrationSetup;
use Dotdigitalgroup\Email\Model\Integration\IntegrationSetupFactory;
use Magento\Backend\Block\Template\Context;

/**
 * Chat template
 *
 * @api
 */
class StudioChat extends \Magento\Backend\Block\Template implements EngagementCloudTrialInterface
{
    use HandlesMicrositeRequests;

    /**
     * @var IntegrationSetupFactory
     */
    private $integrationSetupFactory;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var OauthValidator
     */
    private $oauth;

    /**
     * StudioChat constructor.
     * @param Context $context
     * @param IntegrationSetupFactory $integrationSetupFactory
     * @param Data $helper
     * @param Config $config
     * @param OauthValidator $oauth
     */
    public function __construct(
        Context $context,
        IntegrationSetupFactory $integrationSetupFactory,
        Data $helper,
        Config $config,
        OauthValidator $oauth
    ) {
        $this->integrationSetupFactory = $integrationSetupFactory;
        $this->helper = $helper;
        $this->config = $config;
        $this->oauth = $oauth;

        parent::__construct($context, []);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAction(): string
    {
        //If API Creds Aren't set
        if (!$this->helper->isEnabled()) {
            return $this->getIntegrationSetup()
                ->getEcSignupUrl($this->getRequest(), IntegrationSetup::SOURCE_CHAT);
        }

        return $this->oauth->createAuthorisedEcUrl($this->config->getChatPortalUrl());
    }
}
