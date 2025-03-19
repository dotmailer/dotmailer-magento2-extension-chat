<?php

namespace Dotdigitalgroup\Email\Test\Unit\Model\Chat;

use Dotdigitalgroup\Chat\Model\Profile\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @var Session
     */
    private $customerSessionMock;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepositoryMock;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepositoryMock;

    /**
     * @var StoreManagerInterface
     */
    private $storeManagerMock;

    /**
     * @var CollectionFactory
     */
    private $collectionMock;

    /**
     * @var Data
     */
    private $dataMock;

    /**
     * @var Customer
     */
    private $customerMock;

    /**
     * @var CustomerInterface
     */
    private $customerInterfaceMock;

    /**
     * @var CartInterface
     */
    private $cartInterfaceMock;

    /**
     * @var Store
     */
    private $storeMock;

    protected function setUp() :void
    {
        $this->customerSessionMock = $this->createMock(Session::class);
        $this->customerRepositoryMock = $this->createMock(CustomerRepositoryInterface::class);
        $this->quoteRepositoryMock = $this->createMock(CartRepositoryInterface::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $this->collectionMock = $this->createMock(CollectionFactory::class);
        $this->customerMock = $this->createMock(Customer::class);
        $this->customerInterfaceMock = $this->createMock(CustomerInterface::class);
        $this->cartInterfaceMock = $this->createMock(CartInterface::class);
        $this->storeMock = $this->createMock(Store::class);

        $this->dataMock = new Data(
            $this->customerSessionMock,
            $this->customerRepositoryMock,
            $this->quoteRepositoryMock,
            $this->storeManagerMock,
            $this->collectionMock
        );
    }

    public function testGetDataForChatUserIfIsLoggedInAndQuoteExists()
    {
        $storeId = 1;
        $loggedInCustomerId = 1;
        $customerId = 1;
        $groupId = 1;
        $quoteId = 1;
        $baseUrl = 'http://magento2.dev';

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->customerSessionMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(true);

        $this->customerSessionMock->expects($this->once())
            ->method('getCustomer')
            ->willReturn($this->customerMock);

        $this->customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($loggedInCustomerId);

        $this->customerRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($loggedInCustomerId)
            ->willReturn($this->customerInterfaceMock);

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getForCustomer')
            ->with($loggedInCustomerId)
            ->willReturn($this->cartInterfaceMock);

        $this->storeMock->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->customerInterfaceMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->customerInterfaceMock->expects($this->once())
            ->method('getGroupId')
            ->willReturn($groupId);

        $this->cartInterfaceMock->expects($this->once())
            ->method('getId')
            ->willReturn($quoteId);

        $result = $this->dataMock->getDataForChatUser();

        $this->assertArrayHasKey("quoteId", $result["customer"]);
        $this->assertArrayHasKey("store", $result);
        $this->assertArrayHasKey("customer", $result);
        $this->assertEquals($result["customer"]["quoteId"], $quoteId);
        $this->assertEquals($result["customer"]["id"], $customerId);
        $this->assertEquals($result["customer"]["groupId"], $groupId);
    }

    public function testGetDataForChatUserIfIsLoggedInAndQuoteNotExists()
    {
        $storeId = 1;
        $loggedInCustomerId = 1;
        $customerId = 1;
        $groupId = 1;
        $baseUrl = 'http://magento2.dev';

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->customerSessionMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(true);

        $this->customerSessionMock->expects($this->once())
            ->method('getCustomer')
            ->willReturn($this->customerMock);

        $this->customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($loggedInCustomerId);

        $this->customerRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($loggedInCustomerId)
            ->willReturn($this->customerInterfaceMock);

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getForCustomer')
            ->with($loggedInCustomerId)
            ->will($this->throwException(new \Magento\Framework\Exception\NoSuchEntityException()));

        $this->storeMock->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->customerInterfaceMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->customerInterfaceMock->expects($this->once())
            ->method('getGroupId')
            ->willReturn($groupId);

        $this->cartInterfaceMock->expects($this->never())
            ->method('getId');

        $result = $this->dataMock->getDataForChatUser();

        $this->assertArrayNotHasKey('quoteId', $result['customer']);
        $this->assertArrayHasKey("store", $result);
        $this->assertArrayHasKey("customer", $result);
        $this->assertEquals($result["customer"]["id"], $customerId);
        $this->assertEquals($result["customer"]["groupId"], $groupId);
    }

    public function testGetDataForChatUserIfIsNotLogged()
    {
        $storeId = 1;
        $baseUrl = 'http://magento2.dev';

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->customerSessionMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(false);

        $this->customerSessionMock->expects($this->never())
            ->method('getCustomer');

        $this->customerRepositoryMock->expects($this->never())
            ->method('getById');

        $this->quoteRepositoryMock->expects($this->never())
            ->method('getForCustomer');

        $this->storeMock->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->cartInterfaceMock->expects($this->never())
            ->method('getId');

        $this->customerInterfaceMock->expects($this->never())
            ->method('getId');

        $this->customerInterfaceMock->expects($this->never())
            ->method('getGroupId');

        $result = $this->dataMock->getDataForChatUser();

        $this->assertArrayNotHasKey('customer', $result);
        $this->assertArrayHasKey("store", $result);
        $this->assertEquals($result["store"]["id"], $storeId);
        $this->assertEquals($result["store"]["url"], $baseUrl);
    }
}
