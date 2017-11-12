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
modules
  modules:category  Create a category modules 创建分类模块
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

1.2.1 $option 中需要的配置

* basePath 根目录地址这个是必须存在的,指向项目的根目录地址
* driver   框架驱动,现在只支持了CI
* modules  模块的名称默认使用admin

1.2.2 创建控制器

 php artisan make:controller controller_name

1.2.3 创建脚手架

 php artisan make:scaffold scaffold_name

 --option

 1. tableName 指定表名

 2. primary   指定主键

 3. softdelete  指定软删除的键名

 4. schema      指定规则名生成脚手架

 5. timestamp   指定时间戳字段

 6. rollback    回滚删除掉生成的脚手架

    字段格式: (name html_type options)

    例如 age text

    Enter validations:

    required|max:20

 多个验证规则用 | 链接,暂时支持的验证规则


 1.2.4 验证规则

 1. required   属性必须存在

 2. max:20     属性最大不超过验证值

 3. min:20     属性最小不超过验证值

 4. number     该属性必须是数字

 5. email      该属性必须是邮箱格式

 6. ip         该属性必须是IP地址格式

 7. string     该属性过滤掉所有特殊字符返回string

1.2.5 html类型

1. text   input text

2. textarea  textarea

3. date   时间类型

4. file   可以上传多个图片的

5. fileOne  上传单图

6. radio    单选 支持radio,key,key 也可以支持 radio,name:value,name:value

7. select   选择框 支持select,key,key 也可以支持 select,name:value,name:value









#### feature

    还有好多需要做的功能

        1. 登录注册 分类 广告图 分类 常用的模块功能
        2. api快速生成