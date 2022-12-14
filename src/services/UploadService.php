<?php
namespace findarace\upload\services;

use findarace\upload\Upload;
use findarace\upload\helpers\UploadHelper;

use Craft;
use craft\base\Component;

class UploadService extends Component
{
    // Public Methods
    // =========================================================================

    public function getAssetFieldByHandleOrId($handleOrId)
    {
    	if(!$handleOrId)
    	{
    		return false;
    	}

    	if(is_numeric($handleOrId))
    	{
			$field = UploadHelper::getFieldById($handleOrId);
    	}
    	else
    	{
    		$field = UploadHelper::getFieldByHandle($handleOrId);
    	}
    	return $field;
    }

    public function getVolumeByHandleOrId($handleOrId)
    {
    	if(!$handleOrId)
    	{
    		return false;
    	}

    	if(is_numeric($handleOrId))
    	{
			$volume = Craft::$app->getVolumes()->getVolumeById($handleOrId);
    	}
    	else
    	{
    		$volume = Craft::$app->getVolumes()->getVolumeByHandle($handleOrId);
    	}
    	return $volume;
    }

    public function getFirstViewableVolume()
    {
    	$viewableVolumes = Craft::$app->getVolumes()->getViewableVolumes();
    	return $viewableVolumes[0] ?? false;
    }

}
