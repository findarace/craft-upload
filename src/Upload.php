<?php
/**
 * Uploadit plugin for Craft CMS 3.x
 *
 * Front end asset upload tools for Craft CMS
 *
 * @link      http://findarace.co.uk
 * @copyright Copyright (c) 2018 Fruit Studios
 */

namespace findarace\upload;

use findarace\upload\services\UploadService;
use findarace\upload\variables\UploadVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class Uploadit
 *
 * @author    Fruit Studios
 * @package   Uploadit
 * @since     1.0.0
 *
 * @property  UploaditServiceService $uploaditloaderService
 */
class Upload extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Uploadit
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'service' => UploadService::class,
        ]);

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'upload/default';
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('upload', UploadVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'upload',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
