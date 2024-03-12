### 安装驱动
#### 一. 使用workerman驱动
```shell
composer require microphp/workerman
```

#### 二. 使用roadrunner驱动
```shell
composer require microphp/roadrunner
```

1. 安装roadrunner二进制文件
    ```shell
    php bin/console roadrunner:get
    ```
2. 修改.env中驱动
    ```dotenv
    SERVER_DRIVER=roadrunner
    ```
#### 三. 使用swoole驱动
```shell
composer require microphp/swoole
```

1. 修改.env中驱动
    ```dotenv
    SERVER_DRIVER=swoole
   ```
2. 发布配置
   ```shell
   php bin/console config:publish swoole
   ```
#### 四. 使用AMP驱动
```shell
composer require microphp/amp
```

### 运行
```shell
php bin/console start
```


### 格式化代码
```shell
composer cs ./app
```
