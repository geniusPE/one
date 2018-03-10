<?php

class SmsTaskReceiverImpl implements CTaskReceiver
{

    /**
     * 任务处理函数
     * @param CTaskEntity $entity
     * @return bool 任务成功或否
     */
    public function handle(CTaskEntity $entity)
    {
        $args = $entity->arguments;
        Sms::impl($args['smcInd'])->sendSms(
            new AuthSms($args['AuthSms']['url'], $args['AuthSms']['account'], $args['AuthSms']['password']),
            $args['sccId'],
            $args['type'],
            $args['phone'],
            $args['message']
        );
        return true;
    }
}