<?php

namespace Dotdigitalgroup\Chat\Plugin;

use Dotdigitalgroup\Email\Model\Connector\Module;

class ModulePlugin
{
    public const MODULE_NAME = 'Dotdigitalgroup_Chat';
    public const MODULE_DESCRIPTION = 'Dotdigital Chat for Magento 2';

    /**
     * @var Module
     */
    private $module;

    /**
     * ModulePlugin constructor.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Before fetch active module details
     *
     * @param Module $module
     * @param array $modules
     * @return array
     */
    public function beforeFetchActiveModules(Module $module, array $modules = [])
    {
        $modules[] = [
            'name' => self::MODULE_DESCRIPTION,
            'version' => $this->module->getModuleVersion(self::MODULE_NAME)
        ];
        return [
            $modules
        ];
    }
}
