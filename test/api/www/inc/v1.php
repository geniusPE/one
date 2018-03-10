<?php
$action = "/api";
$jimOrgCode=getOrgCode("jim");
$jimUserId="2";
?>
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs">
            <li><a href="#通知API" data-toggle="tab">通知API</a></li>
            <li><a href="#资讯API" data-toggle="tab">资讯API</a></li>
            <li><a href="#功能测试API" data-toggle="tab">测试API</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="通知API">
                    <span>
                        <form action="<?php echo $action; ?>" method="GET">
                            <input type="hidden" name="c" value='
                                <?php
                            $s = array(
                                "count" => 1,
                                "cliname" => getCliName(),
                                "cliver" => "1.0",
                                "sessionkey" => "",
                                "source" => "1",
                                "uid" => "",
                                "orgcode" => $jimOrgCode,
                                "market"=>"MZ",
                                "reqs" => array(array(
                                    "version" => "1.0",
                                    "name" => "system.client.upgrade.get",
                                    "params" => array(
                                        "version" => "1.0",
                                    ))
                                )
                            );
                            echo json_encode($s);
                            ?>
                            '/>
                            <input type="submit" value="[<?php echo getCliName(); ?>]检查客户端是否有更新版本"/>
                        </form>
                    </span>
                <span>
                        <form action="<?php echo $action; ?>" method="GET">
                            <input type="hidden" name="c" value='
                                <?php
                            $s = array(
                                "count" => 1,
                                "cliname" => getCliName(),
                                "cliver" => "1.0",
                                "sessionkey" => "",
                                "source" => "1",
                                "uid" => "",
                                "orgcode" => $jimOrgCode,
                                "market"=>"MZ",
                                "reqs" => array(array(
                                    "version" => "1.0",
                                    "name" => "system.rn.upgrade.get",
                                    "params" => array(
                                        "version" => "1.0",
                                    ))
                                )
                            );
                            echo json_encode($s);
                            ?>
                            '/>
                            <input type="submit" value="[<?php echo getCliName(); ?>]检查RN客户端是否有更新版本"/>
                        </form>
                    </span>
                    <span>
                        <form action="<?php echo $action; ?>" method="GET">
                            <input type="hidden" name="c" value='
                                <?php
                            $s = array(
                                "count" => 1,
                                "cliname" => getCliName(),
                                "cliver" => "1.0",
                                "sessionkey" => "",
                                "source" => $source,
                                "uid" => "",
                                "orgcode" => $jimOrgCode,
                                "reqs" => array(array(
                                    "version" => "1.0",
                                    "name" => "system.service.discovery.get",
                                    "params" => array(
                                        "mode" => "D",
                                    ))
                                )
                            );
                            echo json_encode($s);
                            ?>
                            '/>
                            <input type="submit" value="[<?php echo getCliName(); ?>]服务发现"/>
                        </form>
                    </span>

                <span>
                        <form action="<?php echo $action; ?>" method="GET">
                            <input type="hidden" name="c" value='
                                <?php
                            $s = array(
                                "count" => 1,
                                "cliname" => getCliName(),
                                "cliver" => "1.0",
                                "sessionkey" => getSessionKey("jim"),
                                "source" => $source,
                                "uid" => $jimUserId,
                                "orgcode" => $jimOrgCode,
                                "reqs" => array(array(
                                    "version" => "1.0",
                                    "name" => "notice.common.list.get",
                                    "params" => array(
                                        "type" => [],
                                        "pagenum" => 0,//页号，首页传0
                                        "pagesize" => 999999,//每页显示数量，不分页就传个足够大的数字
                                    ))
                                )
                            );
                            echo json_encode($s);
                            ?>
                            '/>
                            <input type="submit" value="[<?php echo getCliName(); ?>]获取通知列表"/>
                        </form>
                    </span>
                    <span>
                        <form action="<?php echo $action; ?>" method="POST">
                            <input type="hidden" name="c" value='
                                <?php
                            $s = array(
                                "count" => 1,
                                "cliname" => getCliName(),
                                "cliver" => "1.0",
                                "sessionkey" => "",
                                "source" => 2,
                                "uid" => $jimUserId,
                                "orgcode" => $jimOrgCode,
                                "reqs" => array(array(
                                    "version" => "1.0",
                                    "name" => "system.multioptions.list.get",
                                    "params" => array(
                                        "items"=>[
                                            ["category"=>"banner",
                                             "optionarray"=>["快乐钱包"],
                                            ],
                                            ["category"=>"协议",
                                             "optionarray"=>["借款协议"],
                                            ],
                                            ["category"=>"系统设置",
                                             "optionarray"=>[],
                                            ],
                                        ],
                                    ))
                                )
                            );
                            echo json_encode($s);
                            ?>
                            '/>
                            <input type="submit" value="[<?php echo getCliName(); ?>]批量获取选项列表"/>
                        </form>
                    </span>

                <span>
                        <form action="<?php echo $action; ?>" method="GET">
                            <input type="hidden" name="c" value='
                                <?php
                            $s = array(
                                "count" => 1,
                                "cliname" => getCliName(),
                                "cliver" => "1.0",
                                "sessionkey" => getSessionKey("jim"),
                                "source" => $source,
                                "uid" => $jimUserId,
                                "orgcode" => $jimOrgCode,
                                "reqs" => array(array(
                                    "version" => "1.0",
                                    "name" => "notice.update.remind.get",
                                    "params" => array(
                                        "userid" => 289,//用户ID
                                    ))
                                )
                            );
                            echo json_encode($s);
                            ?>
                            '/>
                            <input type="submit"
                                   value="[<?php echo getCliName(); ?>]获取通知更新数（<?php echo $jimUserId; ?>）"/>
                        </form>
                    </span>
                <span>
                        <form action="<?php echo $action; ?>" method="POST">
                            <input type="hidden" name="c" value='
                                <?php
                            $s = array(
                                "count" => 1,
                                "cliname" => getCliName(),
                                "cliver" => "1.0",
                                "sessionkey" => getSessionKey("jim"),
                                "source" => $source,
                                "uid" => $jimUserId,
                                "orgcode" => $jimOrgCode,
                                "reqs" => array(array(
                                    "version" => "1.0",
                                    "name" => "notice.item.remove.post",
                                    "params" => array(
                                        "userid" => $jimUserId,//用户ID
                                        "unidarray" => [1, 2, 3],
                                    ))
                                )
                            );
                            echo json_encode($s);
                            ?>
                            '/>
                            <input type="submit" value="[<?php echo getCliName(); ?>]删除通知（<?php echo $jimUserId; ?>）"/>
                        </form>
                    </span>
            </div>

            <div class="tab-pane" id="资讯API">
                <span>
                    <form action="<?php echo $action; ?>" method="GET">
                        <input type="hidden" name="c" value='
                            <?php
                        $s=array(
                            "count"=>1,
                            "cliname"=>getCliName(),
                            "cliver"=>"1.0",
                            "sessionkey"=>getSessionKey("jim"),
                            "source"=>$source,
                            "uid"=>getUserId("jim"),
                            "orgcode"=>getOrgCode("jim"),
                            "reqs"=>array(array(
                                "version"=>"1.0",
                                "name"=>"article.infor.list.get",
                                "params"=>array(
                                    "pagenum"=>0,//页号，首页传0
                                    "pagesize"=>99999,//每页显示数量，不分页就传个足够大的数字
                                ))
                            )
                        );
                        echo json_encode($s);
                        ?>
                        '/>
                        <input type="submit" value="获取资讯列表" />
                    </form>
                </span>

                <span>
                    <form action="<?php echo $action; ?>" method="POST">
                        <input type="hidden" name="c" value='
                            <?php
                        $s=array(
                            "count"=>1,
                            "cliname"=>getCliName(),
                            "cliver"=>"1.0",
                            "sessionkey"=>getSessionKey("jim"),
                            "source"=>$source,
                            "uid"=>getUserId("jim"),
                            "orgcode"=>getOrgCode("jim"),
                            "reqs"=>array(array(
                                "version"=>"1.0",
                                "name"=>"article.infor.action.post",
                                "params"=>array(
                                    "paid"=>"f7b85f34c2ba11e78927d71bdc254766",//文章ID
//                                    "action"=>"AGR",//动作类型（AGR：顶，RDR：已读）
                                    "action"=>"RDR",//动作类型（AGR：顶，RDR：已读）
                                ))
                            )
                        );
                        echo json_encode($s);
                        ?>
                        '/>
                        <input type="submit" value="资讯动作" />
                    </form>
                </span>

                <span>
                    <form action="<?php echo $action; ?>" method="GET">
                        <input type="hidden" name="c" value='
                            <?php
                        $s=array(
                            "count"=>1,
                            "cliname"=>getCliName(),
                            "cliver"=>"1.0",
                            "sessionkey"=>getSessionKey("jim"),
                            "source"=>$source,
                            "uid"=>getUserId("jim"),
                            "orgcode"=>getOrgCode("jim"),
                            "reqs"=>array(array(
                                "version"=>"1.0",
                                "name"=>"article.infor.info.get",
                                "params"=>array(
                                    "paid"=>"f7b85f34c2ba11e78927d71bdc254766",//文章ID
                                ))
                            )
                        );
                        echo json_encode($s);
                        ?>
                        '/>
                        <input type="submit" value="获取资讯详情" />
                    </form>
                </span>
            </div>

            <div class="tab-pane" id="功能测试API">
            </div>

        </div>
    </div>
    <div>
        <br />
    </div>
</div>
