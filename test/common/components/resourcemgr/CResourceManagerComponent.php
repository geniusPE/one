<?php

/**
 * 资源管理组件
 */
class CResourceManagerComponent extends CComponent {
    const ROOT_URL_KEY="params.dl.url.key";
    const ROOT_PATH_KEY="params.dl.path.key";
    const FFMPEG_KEY="components.ffmpeg.key";
    const THUMB_KEY="components.thumb.key";
    const PERSISTENT_KEY="persistent.vals";

    private $options=array();

    public function init(){
        if (!array_key_exists(self::ROOT_URL_KEY, $this->options))
            throw new APIException("未传入有效的".self::ROOT_URL_KEY."参数", APIException::$ERR_API_INTERNALERROR);
        if (!array_key_exists(self::ROOT_PATH_KEY, $this->options))
            throw new APIException("未传入有效的".self::ROOT_PATH_KEY."参数", APIException::$ERR_API_INTERNALERROR);
        if (!array_key_exists(self::FFMPEG_KEY, $this->options))
            throw new APIException("未传入有效的".self::FFMPEG_KEY."参数", APIException::$ERR_API_INTERNALERROR);
        if (!array_key_exists(self::THUMB_KEY, $this->options))
            throw new APIException("未传入有效的".self::THUMB_KEY."参数", APIException::$ERR_API_INTERNALERROR);
        if (!array_key_exists(self::PERSISTENT_KEY, $this->options))
            throw new APIException("未传入有效的".self::PERSISTENT_KEY."参数", APIException::$ERR_API_INTERNALERROR);
    }

    public function setOptions($options){
        $this->options=$options;
    }

    public function setPersistentToDb($persistentDb){
        $this->persistentToDb=$persistentDb;
    }

    /**
     * @return ResourceManager
     */
    public function getInstance(){
        $result = new ResourceManager();
        if (Yii::app()->hasComponent($this->options[self::FFMPEG_KEY])){
            $t=Yii::app()->getComponent($this->options[self::FFMPEG_KEY]);
            $result->setFfMpeg($t);
        }
        if (Yii::app()->hasComponent($this->options[self::THUMB_KEY])){
            $t=Yii::app()->getComponent($this->options[self::THUMB_KEY]);
            $result->setThumbFactory($t);
        }

        $t=$this->options[self::ROOT_PATH_KEY];
        $result->setRootPath(Yii::app()->params[$t]);
        $t=$this->options[self::ROOT_URL_KEY];
        $result->setRootUrl(Yii::app()->params[$t]);
        $t=$this->options[self::PERSISTENT_KEY];
        $result->setPersistentToDb($t);
        return $result;
    }
}