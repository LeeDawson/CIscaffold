# 一个脚手架快速生成工具

### 1 安装
### 2 使用
### 3 验证
### 4 源码解析
### 5 feature



#### 安装

composer require torvalds8462/ci-scaffold



#### 使用

在入口文件平行的目录建立一个文件 artisan 可以随意命名

<pre><code>
include "./vendor/autoload.php";
use OutSource\Kernel\Application;
$option = [
    "basePath" => dirname(__FILE__),
    "driver" => 'CI',
];
$application = new Application($option);
$application->run();

</code></pre>


然后cli到这个目录 php artisan
就看到了可用的命令界面


##### 1.1 现在支持的功能
<pre><code>
make
   make:controller  Create a controller   //创建一个控制器
   make:library     Create a library      //创建一个类库
   make:model       Create a model        //创建一个model
   make:scaffold    Create a full CRUD views for given model  //创建一个脚手架
   make:schema      Create a schema       //创建一个规则文件
publish
   publish:init     Publishes init base   //初始化CI框架需要的资源
   publish:layout   Publishes all template files   //导出css js 视图模板
</code></pre>

##### 1.2 配置

$option 中需要的配置
    * basePath 根目录地址这个是必须存在的,指向项目的根目录地址
    * driver   为什么框架驱动,现在只支持了CI





#### feature

    还有好多需要做的功能

        1. 登录注册 分类 广告图 分类 常用的模块功能
        2. api快速生成