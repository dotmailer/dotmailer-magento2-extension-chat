<?php

namespace Dotdigitalgroup\Chat\CustomerData;

use Dotdigitalgroup\Chat\Model\Config;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Dotdigitalgroup\Email\Helper\Data;
use Magento\TestFramework\Event\Magento;

class Chat implements SectionSourceInterface, ArgumentInterface
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
     * @var Data
     */
    private $helper;

    /**
     * Chat constructor.
     *
     * @param Config $config
     * @param Data $helper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Config $config,
        Data $helper,
        StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
    }

    /**
     * Get section data for localstorage
     *
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSectionData()
    {
        return [
            'isEnabled' => $this->config->isChatEnabled(),
            'apiHost' => $this->config->getApiHost(),
            'apiSpaceId' => $this->config->getApiSpaceId(),
            'customerId' => $this->getCustomerId(),
            'profileEndpoint' => $this->getEndpointWithStoreCode(),
            'cookieName' => Config::COOKIE_CHAT_PROFILE,
        ];
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
