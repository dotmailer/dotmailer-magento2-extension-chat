<?php

namespace Dotdigitalgroup\Chat\Block\Adminhtml\Config\Settings;

use Dotdigitalgroup\Chat\Model\Config;
use Dotdigitalgroup\Email\Helper\Data;
use Dotdigitalgroup\Email\Helper\OauthValidator;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

abstract class ButtonField extends Field
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var OauthValidator
     */
    private $oauthValidator;

    /**
     * ButtonField constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param Data $helper
     * @param OauthValidator $oauthValidator
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Data $helper,
        OauthValidator $oauthValidator,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->config = $config;
        $this->oauthValidator = $oauthValidator;
        parent::__construct($context, $data);
    }

    /**
     * Returns the class name based on API Creds validation
     *
     * @return string
     */
    public function getCssClass()
    {
        if ($this->config->getApiSpaceId()) {
            return 'ddg-enabled-button';
        }
        return 'ddg-disabled-button';
    }

    /**
     * Get Button url
     *
     * @return string
     */
    abstract protected function getButtonUrl();

    /**
     * Get HTML element
     *
     * @param AbstractElement $element
     * @return string
     * @throws LocalizedException
     */
    public function _getElementHtml(AbstractElement $element)
    {

         $block = $this->getLayout()->createBlock(Button::class);
         /** @var \Magento\Framework\View\Element\AbstractBlock $block */
         return $block->setType('button')
            ->setLabel(__('Configure'))
            ->setOnClick(sprintf("window.open('%s','_blank')", $this->getButtonUrl()))
            ->setData('class', $this->getCssClass())
            ->toHtml();
    }

    /**
     * Removes use Default Checkbox
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Get Dotdigital authorised url
     *
     * @param string $url
     * @return string
     */
    protected function getEcAuthorisedUrl($url)
    {
        return $this->oauthValidator->createAuthorisedEcUrl($url, 'false');
    }
}
