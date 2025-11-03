<?php
declare(strict_types=1);

namespace Dotdigitalgroup\Chat\ViewModel;

use Dotdigitalgroup\Chat\Model\Config;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

/**
 * ViewModel class for chat profile config.
 */
class ChatProfile implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SecureHtmlRenderer
     */
    private $secureRenderer;

    /**
     * @var Context
     */
    private $context;

    /**
     * Constructor.
     *
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param SecureHtmlRenderer $secureRenderer
     * @param Context $context
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager,
        SecureHtmlRenderer $secureRenderer,
        Context $context
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->secureRenderer = $secureRenderer;
        $this->context = $context;
    }

    /**
     * Render email capture configuration data.
     *
     * @return string
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function renderConfig(): string
    {
        $configData = $this->getChatConfig();

        return $this->secureRenderer->renderTag(
            'script',
            ['type' => 'application/json', 'id' => 'dotdigital-chat-profile-config'],
            json_encode($configData),
            false
        );
    }

    /**
     * Render email capture script.
     *
     * @return string
     */
    public function renderScript(): string
    {
        return $this->secureRenderer->renderTag(
            "script",
            ['src' => $this->context->getAssetRepository()->getUrl(
                'Dotdigitalgroup_Chat::js/chatProfile.js',
            )]
        );
    }

    /**
     * Get chat config for frontend script
     *
     * @return array
     */
    private function getChatConfig()
    {
        try {
            return [
                'isEnabled' => $this->config->isChatEnabled(),
                'apiHost' => $this->config->getApiHost(),
                'apiSpaceId' => $this->config->getApiSpaceId(),
                'customerId' => $this->getCustomerId(),
                'profileEndpoint' => $this->getEndpointWithStoreCode(),
                'cookieName' => Config::COOKIE_CHAT_PROFILE,
            ];
        } catch (NoSuchEntityException | LocalizedException) {
            return [
                'isEnabled' => false,
                'apiHost' => '',
                'apiSpaceId' => '',
                'customerId' => null,
                'profileEndpoint' => '',
                'cookieName' => Config::COOKIE_CHAT_PROFILE,
            ];
        }
    }

    /**
     * Get structured store callback url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    private function getEndpointWithStoreCode()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();
        return $store->getBaseUrl(UrlInterface::URL_TYPE_WEB, true) . Config::MAGENTO_PROFILE_CALLBACK_ROUTE;
    }

    /**
     * Get customer ID
     *
     * @return int|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getCustomerId()
    {
        if ($customer = $this->config->getSession()->getQuote()->getCustomer()) {
            /** @var CustomerInterface $customer */
            return $customer->getId();
        }
        return null;
    }
}
