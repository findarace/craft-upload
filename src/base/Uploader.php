<?php
namespace findarace\upload\base;

use findarace\upload\Upload;
use findarace\upload\base\UploaderInterface;
use findarace\upload\helpers\UploadHelper;
use findarace\upload\assetbundles\upload\UploadAssetBundle;

use Craft;
use craft\web\View;
use craft\base\Model;
use craft\helpers\Json as JsonHelper;
use craft\helpers\UrlHelper;

abstract class Uploader extends Model implements UploaderInterface
{

    // Constants
    // =========================================================================

    const TYPE_VOLUME = 'volume';
    const TYPE_FIELD = 'field';
    const TYPE_USER_PHOTO = 'userPhoto';

    // Private
    // =========================================================================

    public static function type(): string
    {
        return null;
    }

    // Private
    // =========================================================================

    private $_defaultJavascriptVariables;

    // Public
    // =========================================================================

    // ID
    public $id;

    // Assets
    public $assets;

    // Target
    public $target;

    // Settings
    public $enableDropToUpload = true;
    public $enableReorder = true;
    public $enableRemove = true;

    // Styles, Layout & Preview
    public $layout = 'grid'; // grid or list
    public $view = 'auto'; // auto (best guess), image, file, background
    public $customClass;
    public $themeColour = '#000000';
    public $selectText;
    public $dropText;
    public $showUploadIcon = true;

    // Asset
    public $transform = '';
    public $limit;
    public $maxSize;
    public $allowedFileExtensions;


    // Public Methods
    // =========================================================================

    public function __construct()
    {
        $config = Craft::$app->getConfig()->getGeneral();

        // Defualt Settings
        $this->id = uniqid('upload');
        $this->selectText = Craft::t('upload', 'Select files');
        $this->dropText = Craft::t('upload', 'drop files here');
        $this->maxSize = $config->maxUploadFileSize;
        $this->allowedFileExtensions = $config->allowedFileExtensions;

        // Default Javascript Variables
        $this->_defaultJavascriptVariables = [
            'debug' => $config->devMode,
            'csrfTokenName' => $config->csrfTokenName,
            'csrfTokenValue' => Craft::$app->getRequest()->getCsrfToken(),
            'ajaxUrl' => UrlHelper::baseSiteUrl(),
        ];
    }

    public function render()
    {
        $this->validate();

        $view = Craft::$app->getView();
        $view->registerAssetBundle(UploadAssetBundle::class);
        $view->registerJs('new UploadAssets('.$this->getJavascriptVariables().');', View::POS_END);
        $view->registerCss($this->getCustomCss());

        return UploadHelper::renderTemplate('upload/uploader', [
            'uploader' => $this
        ]);
    }

    public function rules(): array
    {
        // IDEA: Should target use this for validation: https://www.yiiframework.com/doc/guide/2.0/en/tutorial-core-validators#filter

        $rules = parent::rules();
        $rules[] = [['id'], 'required'];
        $rules[] = [['maxSize'], 'integer', 'max' => $this->maxSize, 'message' => Craft::t('upload', 'Max file can\'t be greater than the global setting maxUploadFileSize')];
        return $rules;
    }

    public function beforeValidate()
    {
        $this->_checkTransformExists();
        $this->setTarget();
        return parent::beforeValidate();
    }

    public function getJavascriptProperties(): array
    {
        return [
            'id',
            'target',
            'layout',
            'view',
            'limit',
            'maxSize',
            'transform',
            'allowedFileExtensions',
            'enableDropToUpload',
            'enableReorder',
            'enableRemove'
        ];
    }

    public function setTarget(): bool
    {
        return null;
    }

    // Protected Methods
    // =========================================================================

    protected function getJavascriptVariables(bool $encode = true)
    {
        $settings = $this->_defaultJavascriptVariables;
        $settings['type'] = static::type();
        foreach ($this->getJavascriptProperties() as $property)
        {
            $settings[$property] = $this->$property ?? null;
        }

        return $encode ? JsonHelper::encode($settings) : $settings;
    }

    protected function getCustomCss()
    {
      $css = '
        #'.$this->id.' .uploadit--isLoading:after { border-color: '.$this->themeColour.'; }
        #'.$this->id.' .uploadit--label { background-color: '.$this->themeColour.'; }
        #'.$this->id.' .uploadit--btn { color: '.$this->themeColour.'; }
      ';

      return $css;
    }

    // Private Methods
    // =========================================================================

    private function _checkTransformExists()
    {
        if(is_string($this->transform) && !empty($this->transform))
        {
            if(!Craft::$app->getImageTransforms()->getTransformByHandle($this->transform))
            {
                $this->addError('transform', Craft::t('upload', 'Asset transform does not exist'));
                return false;
            }
        }
        return true;
    }
}
