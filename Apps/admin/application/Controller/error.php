<?php

/**
 * 前台 404 模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: error.php 439 2016-10-04 09:47:05Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class errorAction extends service_Controller_admin
{

    /**
     * _init 方法 在执行任何Action前执行
     */
    public function _init()
    {
        $this->assign('action', $_GET['TMAC_ACTION']);
    }

    /**
     * 城市地标 首页
     */
    public function index()
    {
        $this->V('404');
    }

    public function mvc()
    {
        $article_model = Tmac::model('article');       
        $entity_article_base = new \base\entity\Article();        
        $entity_article_base->setArticle_id(5);
        $entity_article_base->setTitle('Windows2003服务器下配置tomcat,solr实现全文索引-2014');
        $article_model->update($entity_article_base);
        exit;
        echo $_SERVER['REMOTE_ADDR'] . '<br>';
        echo Functions::get_client_ip();
    }

}
