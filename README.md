### 安装驱动
#### 一. 安装workerman驱动
```shell
    composer require microphp/workerman
```

#### 二. 安装roadrunner驱动
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
#### 三. 安装swoole驱动
```shell
    composer require microphp/swoole
```

### 运行
```shell
  php bin/console start
```


### 格式化代码
```shell
  composer cs ./app
```