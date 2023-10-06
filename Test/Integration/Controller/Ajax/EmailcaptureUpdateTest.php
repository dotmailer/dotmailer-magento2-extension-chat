<?php

namespace Dotdigitalgroup\Chat\Test\Integration\Controller\Ajax;

use Dotdigitalgroup\Chat\Model\Config;
use Dotdigitalgroup\Chat\Model\Profile\UpdateChatProfile;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\TestFramework\TestCase\AbstractController;

class EmailcaptureUpdateTest extends AbstractController
{
    /**
     * @var UpdateChatProfile
     */
    private $updateChatProfileMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->updateChatProfileMock = $this->createMock(UpdateChatProfile::class);
        ObjectManager::getInstance()->addSharedInstance($this->updateChatProfileMock, UpdateChatProfile::class);
    }

    public function testUpdateChatProfileCookie()
    {
        $profileId = 123456;
        $email = 'chaz@kangaroo.com';
        $_COOKIE[Config::COOKIE_CHAT_PROFILE] = $profileId;

        $this->updateChatProfileMock->expects($this->once())
            ->method('update')
            ->with($profileId, $email);

        $this->getRequest()->setParam('email', $email)->setMethod(HttpRequest::METHOD_POST);

        $this->dispatch('/connector/ajax/emailcapture');
    }
}
