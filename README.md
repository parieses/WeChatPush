# WeChatPush
微信公众号推送和小程序推送
```
公众号推送已经测试可以使用
使用方法
$config = ['appid'=>'公众号appid','secret'=>'公众号的secret']
$type = 1 默认公众号
$data = 公众号及小程序推送数组
$push  = new WeChatPush(1,$config)
$push->setdate($data);
$push->Push;
```
