<?php

/**
 * 后台 文章栏目模块 Controller
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: category.php 439 2016-10-04 09:47:05Z zhangwentao $
 * http://shop.weixinshow.com；
 */
class categoryAction extends service_Controller_admin
{

    private $tmp_model;
    private $check_model;

    /**
     * _init 方法 在执行任何Action前执行
     */
    public function _init()
    {
        $this->assign('action', $_GET['TMAC_ACTION']);
        $this->tmp_model = Tmac::model('Category');
        $this->check_model = $this->M('Check');
        $this->check_model->checkLogin();
        $this->check_model->CheckPurview('tb_admin,tb_editer');
    }

    /**
     * 资讯类别管理 首页
     */
    public function index()
    {
        //TODO  取出所有资讯栏目        
        $parent = 0;
        $category_list = $this->tmp_model->getCategoryList($parent);
        $category_list = empty($category_list) ? '<tr><td colspan="4">请先添加分类</td></tr>' : $category_list;
        $this->assign('category_list', $category_list);
        $this->V('category');
    }

    /**
     * 新增/修改栏目页面
     */
    public function add()
    {
        $cat_id = Input::get('cat_id', 0)->int();
        $button = '添加分类';

        $entity_Category_base = new \base\entity\Category();
        if ($cat_id > 0) {
            $entity_Category_base = $this->tmp_model->getCategoryInfo($cat_id);
            $button = '保存修改';
        }
        //栏目模型分类
        $channeltype = Tmac::config('channel.channeltype');
        $channeltype_option = Utility::Option($channeltype, $entity_Category_base->channeltype);

        //取boolean数组
        $boolean_array = Tmac::config('article.article.boolean');
        $nav_show_array_option = Utility::Option($boolean_array, $entity_Category_base->nav_show);

        //栏目父级 select
        $category_tree_list = $this->tmp_model->getCategoryTreeList(0, $entity_Category_base->cat_pid);

        $this->assign('editinfo', $entity_Category_base);
        $this->assign('category_tree_list', $category_tree_list);
        $this->assign('nav_show_array_option', $nav_show_array_option);
        $this->assign('cat_id', $cat_id);
        $this->assign('button', $button);
        $this->assign('channeltype_option', $channeltype_option);
        $this->V('category');
    }

    /**
     * 新增/修改栏目页面　保存　
     */
    public function save()
    {
        if (empty($_POST) || count($_POST) < 3) {
            $this->redirect('don\'t be evil');
            exit;
        }

        $cat_id = Input::post('cat_id', 0)->int();
        $cat_pid = Input::post('cat_pid', 0)->int();
        //修改的时候父级栏目不能为自己
        if (($cat_id > 0) && ($cat_pid == $cat_id)) {
            $this->redirect('所属栏目父级不能为自己!');
            exit;
        }

        $cat_name = Input::post('cat_name', '')->required('请填写标题！')->string();
        $channeltype = Input::post('channeltype', 0)->int();
        $nav_show = Input::post('nav_show', 0)->int();
        $category_keywords = Input::post('category_keywords', '')->string();
        $category_description = Input::post('category_description', '')->string();
        $category_nicename = Input::post('category_nicename', '')->string();
        $category_content = Input::post('category_content', '')->string();
        $urlfile = Input::post('urlfile', '')->string();
        $cat_order = Input::post('cat_order', 0)->int();

        if (Filter::getStatus() === false) {
            $this->redirect(Filter::getFailMessage());
        }

        //判断category_nicename已经存在
        if (!empty($category_nicename)) {
            $check_cat_id = $this->tmp_model->checkCategoryNicename($category_nicename);
            if ($check_cat_id && ($cat_id != $check_cat_id)) {
                $this->redirect('别名 "' . $category_nicename . '" 已经存在！');
            }
        }

        $entity_Category_base = new \base\entity\Category();
        $entity_Category_base->cat_pid = $cat_pid;
        $entity_Category_base->channeltype = $channeltype;
        $entity_Category_base->cat_name = $cat_name;
        $entity_Category_base->category_keywords = $category_keywords;
        $entity_Category_base->category_description = $category_description;
        $entity_Category_base->category_nicename = $category_nicename;
        $entity_Category_base->category_content = $category_content;
        $entity_Category_base->cat_order = $cat_order;
        $entity_Category_base->urlfile = $urlfile;
        $entity_Category_base->nav_show = $nav_show;

        if ($cat_id > 0) {
            //update save article_class            
            $entity_Category_base->cat_id = $cat_id;
            $rs = $this->tmp_model->modifyCategoryByPk($entity_Category_base);
            if ($rs) {
                $this->redirect('修改新分类成功', PHP_SELF . '?m=category');
            } else {
                $this->redirect('修改新分类失败');
            }
        } else {
            //insert save article_class
            $rs = $this->tmp_model->createCategory($entity_Category_base);
            if ($rs) {
                $this->redirect('添加新分类成功', PHP_SELF . '?m=category');
            } else {
                $this->redirect('添加新分类失败');
            }
        }
    }

    /**
     * del
     * @param int $class_id
     */
    public function del()
    {
        //权限
        $this->check_model->CheckPurview('tb_admin');
        $cat_id = empty($_GET['cat_id']) ? 0 : (int) $_GET['cat_id'];
        if (empty($cat_id)) {
            $this->redirect('请选择要删除的分类，请重试！');
            exit;
        }
        $rs = $this->tmp_model->deleteCategoryById($cat_id);
        // TODO DEL该分类下的所有资讯
        if ($rs) {
            $this->redirect('删除分类成功', PHP_SELF . '?m=category');
        } else {
            $this->redirect('删除分类失败，请重试！');
        }
    }

}
