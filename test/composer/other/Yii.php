<?php

class Yii
{

    public static function app()
    {
        return new AppYii();
    }

}

class AppYii
{
    /** @var CDiaryRollLogger */
    public $bizlogger;
}

Yii::app()->bizlogger;