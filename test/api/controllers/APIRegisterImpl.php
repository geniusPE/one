<?php

/**
 * API注册器实现类
 */
class APIRegisterImpl implements APIRegister {
    /**
     * @param APIDispatcher | APIDispatcher2  $dispatcher
     * @return void
     */
    public function registerAPIs($dispatcher) {
        $dispatcher->registerAPI( "system.client.upgrade.get", "1.0", "SystemClientUpgrade_1_0", "检查客户端是否存在更新", true, false, 0);
        $dispatcher->registerAPI( "system.rn.upgrade.get", "1.0", "SystemRnUpgrade_1_0", "检查RN客户端是否存在更新",
            true, false, 0);
        $dispatcher->registerAPI( "system.service.discovery.get", "1.0", "SystemServiceDiscovery_1_0", "服务发现", true, true);
        $dispatcher->registerAPI( "system.multioptions.list.get", "1.0", "SystemMultiOptionsList_1_0", "批量获取选项列表", false, false, self::C_DUP_REQUEST_FREEZE_MSEC);
        //通知
        $dispatcher->registerAPI( "notice.common.list.get", "1.0", "NoticeCommonList_1_0", "获取通知列表", false, true );
        $dispatcher->registerAPI( "notice.update.remind.get", "1.0", "NoticeUpdateRemind_1_0", "获取通知的更新提醒", false, false);
        $dispatcher->registerAPI( "notice.item.remove.post", "1.0", "NoticeItemRemove_1_0", "删除通知", false, false, self::C_DUP_REQUEST_FREEZE_MSEC);
        //资讯文章
        $dispatcher->registerAPI('article.infor.list.get','1.0','ArticleInforList_1_0','获取资讯列表',true,true);
        $dispatcher->registerAPI('article.infor.info.get','1.0','ArticleInforInfo_1_0','获取资讯详情',false,true);
        $dispatcher->registerAPI('article.infor.action.post','1.0','ArticleInforAction_1_0','资讯动作',false,false, self::C_DUP_REQUEST_FREEZE_MSEC);
        //自检用
        $dispatcher->registerAPI( "system.hello.world.get", "1.0", "HelloWorld_1_0", "自检操作", true, true, self::C_DUP_REQUEST_FREEZE_MSEC);
    }
}
