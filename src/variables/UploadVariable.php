<?php
namespace findarace\upload\variables;

use findarace\upload\models\Uploader; // DEPRICIATE

use findarace\upload\Upload;
use findarace\upload\assetbundles\upload\UploadAssetBundle;
use findarace\upload\base\UploaderInterface;
use findarace\upload\models\VolumeUploader;
use findarace\upload\models\FieldUploader;
use findarace\upload\models\UserPhotoUploader;

use Craft;
use craft\web\View;
use craft\helpers\Template as TemplateHelper;
use craft\helpers\Json as JsonHelper;

class UploadVariable
{
    // Public Methods
    // =========================================================================

    public function volumeUploader($attributes = [])
    {
        return $this->_renderUploader(VolumeUploader::class, $attributes);
    }

    public function fieldUploader($attributes = [])
    {
        return $this->_renderUploader(FieldUploader::class, $attributes);
    }

    public function userPhotoUploader($attributes = [])
    {
        return $this->_renderUploader(UserPhotoUploader::class, $attributes);
    }

    // Private Methods
    // =========================================================================

    public function _renderUploader($type, $attributes = [])
    {
        try{
            $uploader = new $type($attributes);
        } catch(\Throwable $exception) {
            $uploader = false;
        }

        if(!$uploader)
        {
            return TemplateHelper::raw('<p>Invalid Uploader!</p>');
        }

        return TemplateHelper::raw($uploader->render());
    }

}
