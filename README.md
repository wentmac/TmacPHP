# TmacPHP
TmacPHP MVC Framework是php mvc框架 OOP

## 项目部署
### nginx/apache配置
项目入口目录在wwwroot深层级，这样保证web目录访问不到程序文件，提高安全性。

    server_name  www.weixinshow.com;    
    root         /var/www/www.weixinshow.com/demos/message/www/wwwroot/;
### 入口文件
    在每个app功能块的 APP_NAME/wwwroot/index.php//代码里有详细注释
### 配置文件
    Project/database.config.php //数据库及URL配置文件
    Project/Tmac.config.php     //框架配置文件
    Project/APP_NAME/application/Config/config.php //每个APP_NAME的配置文件。
    
## 框架使用简介
### default Controller
默认控制器取名 index.php;
```php
class indexAction extends service_Controller_www
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $goods_model = new service_goods_Detail_www();
        $goods_model->setGoodsId ( $goods_id );
        $goods_info = $goods_model->getGoodsDetail();
        $array['title'] = 'TmacPHP MVC Framework';
        $array['goods_info'] = $goods_info;
        $this->assign( $array );
        $this->V( 'index' );
    }
}
```
### View
View层在 APP_NAME/application/View/default/index.tpl
支持标签和原生PHP两种
```php
<html>
 <head>
   <title>Hello World</title>
 </head>
 <body>
 <!--{template inc/header}-->
 
    {loop $result $k $v}
    <li>{$v->title}</li>
    {/loop}
    
    {if empty($title)}
    <h2>没有标题</h2?
    {else}
    <h2>{$title}</h2>
    {/if}
    
   {$content}
 </body>
</html>
<!-下面是原生模板标签-->
<html>
<title><?php echo $title;?></title>

<?php foreach($result AS $k=>$v):?>
    <li><?php echo $v->title;?></li>
<?php endforeach;?>

<?php if ($a == 5): ?>
  <div>等于5</div>
<?php elseif ($a == 6): ?>
  <div>等于5</div>
<?php else: ?>
  <div>不是5就是6</div>
<?php endif; ?>

<?php include Tmac::loadView ( 'inc/header' );?>
<?php include 'inc/com.tpl';?>
</html>

```
## TmacPHP MVC Framework 亮点案例
### 多个APP_NAME共同继承同一个base
```php
//比如在电商项目中的封装了一个促销规则计算处理的函数，
service_goods_Detail_base
protected function getPromotions($goods_id)

//在后台的商品详细页面中需要用到
class service_goods_Detail_admin extends service_goods_Detail_base

//在前台的商品详细页面中需要用到
class service_goods_Detail_www extends service_goods_Detail_base

//这样通过共同继承base中order_Detail就可以实现，同理把所有goods相关共同的方法都可以放在base中
````
### 支持自己添加扩展类库
    框架自带的类库目录 Framework/Plugin/*.class.php //框架的类库目录已经autoload了，直接初始化使用。
    自己扩展项目使用的类库 Project/APP_NAME/application/Plugin/
    例如
```php
//大的类库，有自己目录结构的
$alipay_config = Tmac::config( 'alipay.alipay_config', APP_WWW_NAME );
require_once Tmac::findFile( 'payment/alipay/alipay_notify', APP_WWW_NAME );
$alipaySubmit = new AlipaySubmit( $alipay_config );

//单个php类文件
$pages = Tmac::Plugin('Pages', APP_WWW_NAME);
$pages->setTotal($count);
$pages->setUrl($url);
$pages->setPrepage(20);
$limit = $pages->getSqlLimit();

$img = Tmac::Plugin('images');
$img->loadFile("test.gif")->crop(0,0,100,100)->resize(50,50)->waterMark("mark.png", 'left','center')->save("b.gif");
```

## 性能
    通过简单的helloword加载测试对比，(apache ab).性能远高于TP,CI,YII等框架。具体测试数据正在完善。
    原理
* 第一次运行的时候把框架核心文件编译成一个~runtime.php文件,保证每次请求时的文件加载数量额外的文件及少。
* 实现spl_autoload_register('_tmac_autoload'); 实现class文件自动按需加载的规则。
* 框架核心满足了平时MVC OOP开发的最小需求，没有像ci,yii,tp那样太臃肿复杂的功能银弹。方便灵活扩展，

## CASE
TmacPHP已经成长了4年多了，目前已经有大量站点使用。有过实战检验。
* 住哪网目前线上的版本 http://www.zhuna.cn/ 百万级PV的检验。
* 银品惠微商城 http://yph.weixinshow.com/ 公众号（银品惠）code 在https://github.com/wentmac/weixinshow
* 聚店 http://www.090.cn/ 前后台（供应商，api，商城，会员中心，管理中心）
* 用户的小企业站 http://www.ruixugroup.com/
    
 
## MORE
更多文档请访问wiki https://github.com/wentmac/TmacPHP/wiki

## 项目目录结构
    +Public
    | + common
    | + js
    | + www
      | + css
      | + js
      | + image
    +www
    | + application
      | + Config
      | + Controller
      | + Model
        | + dao
        | + entity
        | + service
      | + Plugin
      | + View
    | + var
    | + wwwroot
    +base
    | + application
      | + Config
      | + Controller
      | + Model
        | + dao
        | + entity
        | + service
      | + Plugin
      | + View
    | + var
    | + wwwroot
    -Tmac.config.php
    -database.config.php
