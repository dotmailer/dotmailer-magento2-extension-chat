<?php

namespace Dotdigitalgroup\Chat\Model\Profile;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class Data
{
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * Data constructor
     *
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param CartRepositoryInterface $quoteRepository
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $orderCollectionFactory
     */
    public function __construct(
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        CartRepositoryInterface $quoteRepository,
        StoreManagerInterface $storeManager,
        CollectionFactory $orderCollectionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->quoteRepository = $quoteRepository;
        $this->storeManager = $storeManager;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * Collects data for chat user
     *
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getDataForChatUser()
    {
        $store = $this->storeManager->getStore();

        if (!$this->customerSession->isLoggedIn()) {
            return $this->getBasePayload($store);
        }

        $loggedInCustomerId = $this->customerSession->getCustomer()->getId();
        if (!$customer = $this->customerRepository->getById($loggedInCustomerId)) {
            return $this->getBasePayload($store);
        }

        try {
            $quote = $this->quoteRepository->getForCustomer($loggedInCustomerId);
        } catch (NoSuchEntityException $e) {
            $quote = null;
        }

        return $this->getCustomerPayload($store, $customer, $quote);
    }

    /**
     * Returns basic payload for all users
     *
     * @param Store $store
     * @return array
     */
    private function getBasePayload(StoreInterface $store)
    {
        return [
            "store" => [
                "id" => $store->getId(),
                "url" => $store->getBaseUrl(
                    UrlInterface::URL_TYPE_WEB,
                    true
                )
            ],
        ];
    }

    /**
     * Returns enhanced payload array for logged-in customers
     *
     * @param StoreInterface $store
     * @param CustomerInterface $customer
     * @param CartInterface|null $quote
     * @return array
     * @throws NoSuchEntityException
     */
    private function getCustomerPayload(
        StoreInterface $store,
        CustomerInterface $customer,
        ?CartInterface $quote = null
    ) {
        $data = $this->getBasePayload($store);

        $data["customer"] = [
            "id" => $customer->getId(),
            "groupId" => $customer->getGroupId(),
            "firstName" => $customer->getFirstName(),
            "lastName" => $customer->getLastName(),
            "email" => $customer->getEmail()
        ];

        if ($quote) {
            $data["customer"]["quoteId"] = $quote->getId();
        }

        return $data;
    }
}
