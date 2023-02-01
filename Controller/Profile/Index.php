<?php

namespace Dotdigitalgroup\Chat\Controller\Profile;

use Dotdigitalgroup\Chat\Model\Profile\UpdateChatProfile;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\HTTP\PhpEnvironment\Response;

class Index implements HttpPostActionInterface
{
    /**
     * @var UpdateChatProfile
     */
    private $chatProfile;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Profile constructor
     *
     * @param Context $context
     * @param UpdateChatProfile $chatProfile
     */
    public function __construct(
        Context $context,
        UpdateChatProfile $chatProfile
    ) {
        $this->chatProfile = $chatProfile;
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
    }

    /**
     * Update the user's profile with Chat
     *
     * @return Response
     */
    public function execute(): Response
    {
        $this->chatProfile->update($this->request->getParam('profileId'));
        $this->response->setHttpResponseCode(204);
        return $this->response;
    }
}
