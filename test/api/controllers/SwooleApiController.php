<?php
/**
 * Created by PhpStorm.
 * User: jim
 * Date: 17-9-7
 * Time: 下午3:11
 */

class SwooleApiController extends AbstractSwooleApiControll {
    public function init() {
        parent::init();
    }

    public function beforeAction($req, $response) {
        //
    }

    public function index($req, $response) {
        $c=$req->get["c"];
        $result=$this->getDispatcher()->dispatcherAction($c);
        return $result;
    }

    public function afterAction($req, $response) {
        //
    }
}
