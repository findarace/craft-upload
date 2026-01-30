<?php

namespace findarace\upload\helpers;

use Craft;
use craft\web\View;
use craft\db\Query;
use craft\fields\Assets as AssetsField;
use craft\helpers\Assets as AssetsHelper;

class UploadHelper
{
    // Template
    // =========================================================================

    public static function renderTemplate(string $template, array $variables = [])
    {
        $view = Craft::$app->getView();
        $currentTemplateMode = $view->getTemplateMode();
        $view->setTemplateMode(View::TEMPLATE_MODE_CP);

        $html = $view->renderTemplate($template, $variables);

        $view->setTemplateMode($currentTemplateMode);
        return $html;
    }

    // Field Map
    // =========================================================================

    public static function getAllowedFileExtensionsByFieldKinds(array $kinds = null)
    {
        if(!$kinds)
        {
            return Craft::$app->getConfig()->getGeneral()->allowedFileExtensions;
        }


        $fileKinds = AssetsHelper::getFileKinds();

        $allowedFileExtensions = [];
        if($kinds)
        {
            foreach($kinds as $kind)
            {
                if(array_key_exists($kind, $fileKinds))
                {
                    $allowedFileExtensions = array_merge($allowedFileExtensions, $fileKinds[$kind]['extensions']);
                }
            }
        }

        $allowedFileExtensionsFromConfig = Craft::$app->getConfig()->getGeneral()->allowedFileExtensions;

        $vaidatedAllowedFileExtensions = [];
        foreach ($allowedFileExtensions as $allowedFileExtension)
        {
            if(in_array($allowedFileExtension, $allowedFileExtensionsFromConfig))
            {
                $vaidatedAllowedFileExtensions[] = $allowedFileExtension;
            }
        }
        return $vaidatedAllowedFileExtensions;
    }

    // Field Map
    // =========================================================================

    private static $_fieldsMapType = AssetsField::class;
    private static $_fieldsMap;

    public static function getFieldsMap()
    {
        self::_buildFieldsMap();
        return self::$_fieldsMap;
    }

    public static function getFieldByHandle(string $handle)
    {
        self::_buildFieldsMap();
        $fieldId = self::$_fieldsMap[$handle] ?? false;
        return $fieldId ? Craft::$app->getFields()->getFieldById($fieldId) : false;
    }

    public static function getFieldById(string $id)
    {
        return Craft::$app->getFields()->getFieldById($id);
    }

    public static function getFieldIdByHandle(string $handle)
    {
        self::_buildFieldsMap();
        return self::$_fieldsMap[$handle] ?? false;
    }

    private static function _buildFieldsMap()
    {
        if (self::$_fieldsMap === null) {

            $fields = (new Query())
                ->select(['id', 'handle', 'context'])
                ->where(['type' => self::$_fieldsMapType])
                ->from(['{{%fields}}'])
                ->all();

            // In Craft 5, Matrix blocks are now entries with entry types
            // Get the relationship between Matrix fields and their entry types through field settings
            $matrixFields = (new Query())
                ->select(['id', 'handle', 'settings'])
                ->where(['type' => 'craft\\fields\\Matrix'])
                ->from(['{{%fields}}'])
                ->all();

            // Build a mapping of entry type handles to their parent field handles
            $entryTypeHandleToField = [];
            foreach ($matrixFields as $matrixField) {
                $settings = json_decode($matrixField['settings'], true);
                if (isset($settings['entryTypes']) && is_array($settings['entryTypes'])) {
                    foreach ($settings['entryTypes'] as $entryTypeConfig) {
                        $entryTypeHandle = $entryTypeConfig['handle'] ?? null;
                        if ($entryTypeHandle) {
                            $entryTypeHandleToField[$entryTypeHandle] = $matrixField['handle'];
                        }
                    }
                }
            }

            // Now get all entry types and match them to their field handles
            $allEntryTypes = (new Query())
                ->select(['id', 'handle'])
                ->from(['{{%entrytypes}}'])
                ->all();

            $matrixFieldsContext = [];
            foreach ($allEntryTypes as $entryType)
            {
                if (isset($entryTypeHandleToField[$entryType['handle']])) {
                    // Build context mapping for nested entry fields (formerly matrix blocks)
                    $fieldHandle = $entryTypeHandleToField[$entryType['handle']];
                    $matrixFieldsContext['entryType:'.$entryType['id']] = $fieldHandle.':'.$entryType['handle'].':';
                }
            }

            $fieldMap = [];
            foreach ($fields as $field)
            {
                if(array_key_exists($field['context'], $matrixFieldsContext))
                {
                    $handle = $matrixFieldsContext[$field['context']].$field['handle'];
                    $fieldMap[$handle] = $field['id'];
                }
                else
                {
                    $fieldMap[$field['handle']] = $field['id'];
                }
            }

            self::$_fieldsMap = $fieldMap;
        }
    }

}
