<?php

namespace Dotdigitalgroup\Chat\Model\Api\Requests;

use Dotdigitalgroup\Chat\Model\Api\LiveChatApiClient;
use Dotdigitalgroup\Chat\Model\Api\LiveChatRequestInterface;
use Dotdigitalgroup\Chat\Model\Config;
use Dotdigitalgroup\Email\Logger\Logger;
use Zend\Http\Response;
use Zend\Http\Request;

class UpdateProfile implements LiveChatRequestInterface
{
    /**
     * @var LiveChatApiClient
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * UpdateProfile constructor
     *
     * @param LiveChatApiClient $client
     * @param Config $config
     * @param Logger $logger
     */
    public function __construct(
        LiveChatApiClient $client,
        Config $config,
        Logger $logger
    ) {
        $this->client = $client;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Send update profile request
     *
     * @param string $profileId
     * @param array $data
     * @return void|Response
     */
    public function send(string $profileId, array $data = [])
    {
        try {
            $response = $this->client->request(
                sprintf('apispaces/%s/profiles/%s', $this->config->getApiSpaceId(), $profileId),
                Request::METHOD_PATCH,
                $data
            );
            return $response;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
