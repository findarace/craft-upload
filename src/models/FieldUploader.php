<?php
namespace findarace\upload\models;

use findarace\upload\Upload;
use findarace\upload\base\Uploader;
use findarace\upload\helpers\UploadHelper;

use Craft;
use craft\base\ElementInterface;
use craft\base\FieldInterface;

class FieldUploader extends Uploader
{

    // Static
    // =========================================================================

    public static function type(): string
    {
        return self::TYPE_FIELD;
    }

    // Public
    // =========================================================================

    public $name;
    public $field;
    public $element;
    public $saveOnUpload = false;

    // Public Methods
    // =========================================================================

    public function __construct(array $attributes = [])
    {
        parent::__construct();

        // Populate
        $this->setAttributes($attributes, false);
    }

    public function rules(): array
    {
        $rules = parent::rules();
        $rules[] = [['name'], 'required'];
        $rules[] = [['target'], 'required', 'message' => Craft::t('upload', 'A valid field and element must be set.')];
        return $rules;
    }

    public function getJavascriptProperties(): array
    {
        $variables = parent::getJavascriptProperties();
        $variables[] = 'name';
        $variables[] = 'saveOnUpload';
        return $variables;
    }

    public function setTarget(): bool
    {
        // Element provided lets check it
        $element = $this->element;
        if($element && !$element instanceof ElementInterface)
        {
            $element = Craft::$app->getElements()->getElementById((int) $this->element);
        }

        // Got an element lets check the field
        $field = $this->field instanceof FieldInterface ? $this->field : false;
        if(!$field)
        {
            $field = Upload::$plugin->service->getAssetFieldByHandleOrId($this->field);
        }

        // Field is a duffer
        if(!$field)
        {
            $this->addError('field', Craft::t('upload', 'Could not locate your field.'));
            return false;
        }

        return $this->_updateTarget($field, $element ? $element : null);
    }

    private function _updateTarget(FieldInterface $field, ElementInterface $element = null)
    {
        // Set any uploader defaults based on the field
        $this->target = [
            'fieldId' => $field->id,
            'elementId' => $element->id ?? ''
        ];
        $this->limit = $field->maxRelations ? $field->maxRelations : null;
        $this->allowedFileExtensions = UploadHelper::getAllowedFileExtensionsByFieldKinds($field->allowedKinds);

        return true;
    }

}
