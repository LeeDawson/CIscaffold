# 一个脚手架快速生成工具

todo

    1. 登录注册 分类 广告图模块功能
    2. api快速生成

# 怎么使用

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




# 现在支持的功能

 make
  make:controller  Create a controller   //创建一个控制器
  make:library     Create a library      //创建一个类库
  make:model       Create a model        //创建一个model
  make:scaffold    Create a full CRUD views for given model  //创建一个脚手架
  make:schema      Create a schema       //创建一个规则文件
 publish
  publish:init     Publishes init base   //初始化CI框架需要的资源
  publish:layout   Publishes all template files   //导出css js 视图模板
