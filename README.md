
# 一个脚手架快速生成工具

todo

    1. 基于CI框架的后台模块快速生成

    2. 登录注册功能

    3. api快速生成


# 怎么使用

和入口文件平行的目录建立一个文件

名字叫啥都可以 比如  artisan

<pre>
include "./vendor/autoload.php";
use OutSource\Kernel\Application;
$option = [
    "basePath" => dirname(__FILE__),
    "driver" => 'CI',
];
$application = new Application($option);
$application->run();
<code>

然后cli到这个目录 php artisan

就看到了可用的命令界面
