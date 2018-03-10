<?php

/**
 * 异步测试实现类
 */
class ASyncTestImpl extends AbstractASyncRunnerImpl implements ASyncRunnable{
    public function getName(){
        return "async.test.test.1";
    }

    public function init(){

    }

    /**
     * @param ASyncEntity $entity
     */
    public function run($entity){
        $time=time();
        $this->getBizLogger()->debugEx("收到异步请求:{$time}".$entity, 15, 'TEST');
        sleep(1);
        print("finish");
//        throw new APIException("TEST", 0);
    }
}
