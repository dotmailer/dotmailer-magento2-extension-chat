<?php

namespace Dotdigitalgroup\Chat\Plugin;

use Dotdigitalgroup\Chat\Model\Config;
use Dotdigitalgroup\Chat\Model\Profile\UpdateChatProfile;
use Dotdigitalgroup\Email\Controller\Ajax\Emailcapture;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\Cookie\CookieReaderInterface;

class EmailcapturePlugin
{
    /**
     * @var UpdateChatProfile
     */
    private $chatProfile;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CookieReaderInterface
     */
    private $cookieReader;

    /**
     * EmailcapturePlugin constructor.
     *
     * @param UpdateChatProfile $chatProfile
     * @param CookieReaderInterface $cookieReader
     * @param Context $context
     */
    public function __construct(
        UpdateChatProfile $chatProfile,
        CookieReaderInterface $cookieReader,
        Context $context
    ) {
        $this->chatProfile = $chatProfile;
        $this->cookieReader = $cookieReader;
        $this->request = $context->getRequest();
    }

    /**
     * After email capture execute
     *
     * @param Emailcapture $emailcapture
     * @param ResultInterface $result
     */
    public function afterExecute(Emailcapture $emailcapture, $result)
    {
        // if a chat profile ID is present, update chat profile data
        if ($chatProfileId = $this->cookieReader->getCookie(Config::COOKIE_CHAT_PROFILE, null)) {
            $this->chatProfile->update($chatProfileId, $this->request->getParam('email'));
        }

        return $result;
    }
}
