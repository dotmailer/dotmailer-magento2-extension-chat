<?php

namespace Dotdigitalgroup\Chat\Observer;

use Dotdigitalgroup\Chat\Model\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Dotdigitalgroup\Chat\Model\Profile\UpdateChatProfile;
use Magento\Framework\Stdlib\Cookie\CookieReaderInterface;

class CustomerLogin implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var UpdateChatProfile
     */
    private $chatProfile;

    /**
     * @var CookieReaderInterface
     */
    private $cookieReader;

    /**
     * CustomerLogin constructor.
     *
     * @param RequestInterface $request
     * @param UpdateChatProfile $chatProfile
     * @param CookieReaderInterface $cookieReader
     */
    public function __construct(
        RequestInterface $request,
        UpdateChatProfile $chatProfile,
        CookieReaderInterface $cookieReader
    ) {
        $this->request = $request;
        $this->chatProfile = $chatProfile;
        $this->cookieReader = $cookieReader;
    }

    /**
     * Run observer
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $chatProfileId = $this->cookieReader->getCookie(Config::COOKIE_CHAT_PROFILE, null);
        if ($chatProfileId) {
            $this->chatProfile->update($chatProfileId);
        }
    }
}
