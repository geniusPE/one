#!/usr/bin/python
# coding=utf-8

import sys
import os

# php必须在/usr/local/php/bin目录下，不然会除错
# runcmd.py必须是可执行
# runcmd 的参数必须是个Yii的Command，用yiic命令可以得到
if __name__=="__main__":
    if (len(sys.argv) != 2):
        raise Exception("run parameters error, must be runcmd.py <commandname>")
    curDir=os.path.split(os.path.realpath(sys.argv[0]))[0]
    os.chdir(curDir)
    cmd="/usr/local/php/bin/php yiic.php "+sys.argv[1]
    print("working dir: "+curDir, "command:", cmd)
    ret=os.popen(cmd).readlines()
    for item in ret:
        t=item
        if item.endswith("\n"):
            t=item[:-1]
        print(t)
