# 介绍
短信SMS，模块是基于[easy-sms](https://github.com/overtrue/easy-sms) 封装的模块，目前支持对阿里云、腾讯云和七牛云配置（如果有需要可看easy-sms文档进行调整）。该模块可以直接在管理后台对配置信息进行修改，还可以查看每次发送的记录和结果信息。

# 安装

```shell
php bin/hyperf.php hcms:install sms
```
### composer 依赖
```shell
 composer require "overtrue/easy-sms"
```

# 配置
- 选择短信平台
- 根据不同短信平台填写所需的配置信息
![](http://watershop.oss-cn-qingdao.aliyuncs.com/20220302173205d5d7b1645.png)

# 记录
- 记录发送状态和结果信息

![img.png](http://watershop.oss-cn-qingdao.aliyuncs.com/20220302173423875b66149.png)

## 测试

![img_1.png](http://watershop.oss-cn-qingdao.aliyuncs.com/202203021735136174a9909.png)