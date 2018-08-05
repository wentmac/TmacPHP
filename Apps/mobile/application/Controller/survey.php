<?php

/**
 * mobile 问卷 模块 Controller
 * ============================================================================
 * 宝身茶　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: goods.php 874 2017-02-03 03:20:03Z zhangwentao $
 * http://shop.weixinshow.com；
 */

use mobile\service\survey\SurveyQuestion as SurveyQuestion;

class surveyAction extends service_Controller_mobile
{

    private $score_detail_ary;

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }


    /**
     * @param $step_id
     * 传入step_id跳到 该问卷页面
     */
    private function jumpSurveyStep( $step_method )
    {
        if ( method_exists( get_class(), $step_method ) !== false ) {
            call_user_func( array( get_class(), $step_method ) );
        } else {
            $this->assign( 'step_id', 1 );
            $this->step1();
        }
    }

    /**
     * ajax 更新接口
     * @param \base\entity\Survey $entity_Survey
     * @throws ApiException
     */
    public function save()
    {
        $step = Input::post( 'id', 0 )->required( '问卷步骤不能为空' )->int();
        $survey_result = Input::post( 'survey_result', 0 )->required( '问卷答案不能为空' )->sql();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() );
        }

        //通过step_id 查找survey_field
        $step_id_field = Tmac::config( 'survey.step_id_field' );
        $survey_field = empty( $step_id_field[ $step ] ) ? '' : $step_id_field[ $step ];
        if ( empty( $survey_field ) ) {
            throw new ApiException( '问卷不存在' );
        }

        $entity_Survey = new \base\entity\Survey();
        $entity_Survey->step_id = $step;
        $entity_Survey->uid = $this->memberInfo->uid;

        $survey_model = new SurveyQuestion();
        $entity_Survey->uid = $this->memberInfo->uid;

        $updateResult = $survey_model->setSurveyField( $survey_field )
            ->setSurveyResult( $survey_result )
            ->setMemberInfo( $this->memberInfo )
            ->updateSurvey( $entity_Survey );
        if ( $updateResult === false ) {
            throw new ApiException( $survey_model->getErrorMessage() );
        }


        $return[ 'next_step_id' ] = $updateResult;

        $this->apiReturn( $return );
    }

    /**
     * 检测是否男女不同的问卷是否有权限
     * @param $step_id
     * @param $check_un_completed
     */
    private function checkSurveyPurview( $step_id, $check_un_completed )
    {
        if ( $step_id <> SurveyQuestion::question_male_physiological_condition
            && $step_id <> SurveyQuestion::question_female_physiological_condition ) {
            return true;
        }

        if ( $check_un_completed->sex == SurveyQuestion::man ) {
            //如果是男生  不出现 女性生理情况-多选
            $next_step_id = SurveyQuestion::question_male_physiological_condition;
        } else if ( $check_un_completed->sex == SurveyQuestion::women ) {
            //如果是女生  不出现 男性生理情况-多选
            $next_step_id = SurveyQuestion::question_female_physiological_condition;
        }
        return $next_step_id;
    }

    /**
     * 计算下一个问卷项目的跳转链接
     * @param $check_un_completed
     * @param $step_id
     */
    private function jumpNext( $check_un_completed )
    {
        if ( $check_un_completed->step_id == SurveyQuestion::question_stool_condition ) {
            if ( $check_un_completed->sex == SurveyQuestion::man ) {
                //如果是男生  不出现 女性生理情况-多选
                $next_step_id = SurveyQuestion::question_male_physiological_condition;
            } else if ( $check_un_completed->sex == SurveyQuestion::women ) {
                //如果是女生  不出现 男性生理情况-多选
                $next_step_id = SurveyQuestion::question_female_physiological_condition;
            }
            $next_step_method = 'step' . $next_step_id;
        } elseif ( $check_un_completed->step_id == SurveyQuestion::question_female_physiological_condition ) {
            $next_step_id = SurveyQuestion::question_allergic_condition;
            $next_step_method = 'step' . $next_step_id;
        } elseif ( $check_un_completed->step_id == SurveyQuestion::question_allergic_condition ) {
            //最后一个问题了。开始判断是否有最高分重复的。
            $score_detail_array = json_decode( $check_un_completed->score_detail, true );
            $score = $check_un_completed->score;

            arsort( $score_detail_array );//根据值，以降序对关联数组进行排序   .最高分->最低分排序

            $score_detail_ary = [];
            foreach ( $score_detail_array as $label_id => $score_value ) {
                $score_detail_ary[ $score_value ][] = $label_id;
            }
            if ( !empty( $score_detail_ary[ $score ] ) && count( $score_detail_ary[ $score ] ) > 1 && empty( $check_un_completed->physique_type_condition ) ) {
                //有重复的,需要进入确认页面
                $next_step_id = 'confirm';
                $this->score_detail_ary = $score_detail_ary;
            } else {
                //没有重复的，直接进入结果页面。
                $next_step_id = 'result';
                //更新 结果
                $entity_Survey = new \base\entity\Survey();
                $entity_Survey->physique_condition = current( array_keys( $score_detail_array ) );
                $survey_model = new SurveyQuestion();
                $survey_model->updateSurveyById( $check_un_completed->survey_id, $entity_Survey );
            }
            $next_step_method = $next_step_id;
        } else {
            $next_step_id = intval( $check_un_completed->step_id ) + 1;
            $next_step_method = 'step' . $next_step_id;
        }
        $this->assign( 'step_id', $next_step_id );
        $this->assign( 'prev_step_id', $check_un_completed->step_id );
        return $next_step_method;

    }

    public function confirm()
    {
        $survey_model = new SurveyQuestion();
        $check_un_completed = $survey_model->setMemberInfo( $this->memberInfo )->checkUnFinishedSurvey();
        if ( $check_un_completed === true ) { //问卷调查都已经结束，直接重新开始
            $this->assign( 'step_id', 2 );
            return $this->step2();
        } else if ( empty( $check_un_completed->physique_condition ) ) {//如果还未到最后一步，不能查看结果页
            $step_id = $this->jumpNext( $check_un_completed );
            if ( $step_id <> 'confirm' ) {
                return $this->jumpSurveyStep( $step_id );
            }
        }

        $confirm_array = current( $this->score_detail_ary );
        $setting_array = Tmac::config( 'survey.physique_condition_result' );


        $option_array = [
            'name' => '下面的情况您更关心哪一个？',
            'type' => 'radio',
            'step_id' => '2',
            'object' => []
        ];

        $object = [];
        foreach ( $confirm_array as $v ) {
            if ( !empty( $setting_array[ $v ] ) ) {
                $object[] = $setting_array[ $v ];
            }
        }
        $option_array[ 'object' ] = $object;


        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = 'result';

        $this->assign( $array );
        $this->V( 'step/confirm' );
    }

    public function confirm_save()
    {
        $survey_model = new SurveyQuestion();
        $check_un_completed = $survey_model->setMemberInfo( $this->memberInfo )->checkUnFinishedSurvey();
        if ( $check_un_completed === true ) { //问卷调查都已经结束，直接重新开始
            throw new ApiException( '问卷已经结束' );
        } else if ( !empty( $check_un_completed->physique_condition ) ) {//如果还未到最后一步，不能查看结果页
            throw new ApiException( '问卷还未到最后一个问题' );
        }


        $survey_result = Input::post( 'survey_result', 0 )->required( '问卷答案不能为空' )->int();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() );
        }

        $score_detail_array = json_decode( $check_un_completed->score_detail, true );
        $score = $check_un_completed->score;

        arsort( $score_detail_array );//根据值，以降序对关联数组进行排序   .最高分->最低分排序

        $score_detail_ary = [];
        foreach ( $score_detail_array as $label_id => $score_value ) {
            $score_detail_ary[ $score_value ][] = $label_id;
        }
        if ( empty( $score_detail_ary[ $score ] ) || count( $score_detail_ary[ $score ] ) == 1 ) {
            throw new ApiException( '问卷不需要复选' );
        }
        //判断选项是不是结果集中
        if ( !in_array( $survey_result, $score_detail_ary[ $score ] ) ) {
            throw new ApiException( '问卷选项异常' );
        }

        //更新 结果
        $entity_Survey = new \base\entity\Survey();
        $entity_Survey->physique_type_condition = $survey_result;
        $entity_Survey->physique_condition = $survey_result;
        $survey_model = new SurveyQuestion();
        $survey_model->updateSurveyById( $check_un_completed->survey_id, $entity_Survey );

        $this->apiReturn();
    }


    public function completed()
    {
        $survey_model = new SurveyQuestion();
        $check_un_completed = $survey_model->setMemberInfo( $this->memberInfo )->checkUnFinishedSurvey();
        if ( $check_un_completed === true ) { //问卷调查都已经结束，直接重新开始
            throw new ApiException( '问卷已经结束' );
        } else if ( empty( $check_un_completed->physique_condition ) ) {//如果还未到最后一步，不能查看结果页
            throw new ApiException( '问卷还未出结果' );
        }

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() );
        }

        //更新 结果
        $entity_Survey = new \base\entity\Survey();
        $entity_Survey->is_completed = SurveyQuestion::is_completed_yes;
        $survey_model = new SurveyQuestion();
        $survey_model->updateSurveyById( $check_un_completed->survey_id, $entity_Survey );

        $this->apiReturn();
    }

    public function result()
    {
        $survey_model = new SurveyQuestion();
        $check_un_completed = $survey_model->setMemberInfo( $this->memberInfo )->checkUnFinishedSurvey();
        if ( $check_un_completed === true ) { //问卷调查都已经结束，直接重新开始
            $this->assign( 'step_id', 2 );
            return $this->step2();
        } else if ( empty( $check_un_completed->physique_condition ) ) {//如果还未到最后一步，不能查看结果页
            $step_id = $this->jumpNext( $check_un_completed );
            if ( $step_id <> 'result' ) {
                return $this->jumpSurveyStep( $step_id );
            }
        }

        $setting_array = Tmac::config( 'survey.physique_condition_result' );

        $option_array = [
            'name' => '评测结果',
            'type' => 'radio',
            'step_id' => '2',
            'object' => [
                [ 'name' => '男', 'image_name' => 'sex_man', 'value' => 1 ],
                [ 'name' => '女', 'image_name' => 'sex_women', 'value' => 2 ]
            ]
        ];


        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;
        $array[ 'result_label' ] = $setting_array[ $check_un_completed->physique_condition ][ 'label' ];
        $array[ 'result_score' ] = $check_un_completed->score;
        $array[ 'label_id' ] = $check_un_completed->physique_condition;

        $this->assign( $array );
        $this->V( 'step/result' );
    }

    public function index()
    {
        $step = Input::get( 'id', 0 )->int();

        $survey_model = new SurveyQuestion();
        $check_un_completed = $survey_model->setMemberInfo( $this->memberInfo )->checkUnFinishedSurvey();

        if ( $check_un_completed !== true ) { //有未完成的问卷调查，直接跳过去
            if ( !empty( $step ) && $step <= $check_un_completed->step_id ) {
                $prev_step_id = $step - 1;
                $check_step_id = $this->checkSurveyPurview( $step, $check_un_completed );
                if ( $check_step_id !== true ) {
                    $step = $check_step_id;
                    $prev_step_id = SurveyQuestion::question_stool_condition;
                }
                $this->assign( 'step_id', $step );
                $this->assign( 'prev_step_id', $prev_step_id );
                return $this->jumpSurveyStep( 'step' . $step );
            }
            $step_id = $this->jumpNext( $check_un_completed );
            $this->jumpSurveyStep( $step_id );
        } else if ( $step == 1 ) {//开始新的问卷调查
            $this->assign( 'step_id', 1 );
            $this->assign( 'prev_step_id', 1 );
            $this->step1();
        } else {//开始新的问卷调查
            $this->assign( 'prev_step_id', 1 );
            $this->assign( 'step_id', 2 );
            $this->step2();
        }
    }

    /**
     * 问卷页面1
     */
    private function step1()
    {
        $array[ 'buy_now_button' ] = 1;

//           echo '<pre>';
//           print_r( $array );
//         echo "</pre>";
//        die;

        $this->assign( $array );
        $this->V( 'step/1' );
    }

    /**
     * 问卷页面2
     * 我的性别-单选
     */
    private function step2()
    {
        $option_array = Tmac::config( 'survey.sex' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面3
     * 我的体形
     */
    private function step3()
    {
        $option_array = Tmac::config( 'survey.body_shape' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面4
     * 我的舌象
     */
    private function step4()
    {
        $option_array = Tmac::config( 'survey.tongue' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面5
     * 精神面貌
     */
    private function step5()
    {
        $option_array = Tmac::config( 'survey.vigor' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面6
     * 一年内感冒情况-多选
     */
    private function step6()
    {
        $option_array = Tmac::config( 'survey.cold_situation' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面7
     * 皮肤情况-多选
     */
    private function step7()
    {
        $option_array = Tmac::config( 'survey.skin_situation' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面8
     * 对寒热的感觉
     */
    private function step8()
    {
        $option_array = Tmac::config( 'survey.feeling_cold_heat' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面9
     * 口中感觉
     */
    private function step9()
    {
        $option_array = Tmac::config( 'survey.feeling_in_the_mouth' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面10
     * 大便情况
     */
    private function step10()
    {
        $option_array = Tmac::config( 'survey.stool_condition' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;


        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面11
     * 女性生理期情况
     */
    private function step11()
    {
        $option_array = Tmac::config( 'survey.female_physiological_condition' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面12
     * 男性生理期情况
     */
    private function step12()
    {
        $option_array = Tmac::config( 'survey.male_physiological_condition' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }

    /**
     * 问卷页面13
     * 过敏情况
     */
    private function step13()
    {
        $option_array = Tmac::config( 'survey.allergic_condition' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }


    /**
     * 问卷页面14
     * 下面两种情况您更关心哪一个？
     */
    private function step14()
    {
        $option_array = Tmac::config( 'survey.physique_type_condition' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }


    /**
     * 问卷页面15
     * 体质标签
     */
    private function step15()
    {
        $option_array = Tmac::config( 'survey.physique_condition' );

        $array[ 'option_array' ] = $option_array;
        $array[ 'next_step_id' ] = $option_array[ 'step_id' ] + 1;

        $this->assign( $array );
        $this->V( 'step/' . $option_array[ 'type' ] );
    }


    public function index1()
    {
        $goods_id = Input::get( 'id', 0 )->required( '商品不能为空' )->int();
        $agent_id = Input::get( 'agent', 0 )->int();

        if ( Filter::getStatus() === false ) {
            $this->redirect( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }

        if ( !empty( $agent_id ) ) {
            $expire_time = 86400 * 7;
            setcookie( 'agent_id', $agent_id, $this->now + $expire_time, '/', $GLOBALS [ 'TmacConfig' ] [ 'Cookie' ] [ 'domain' ] );
        }

        $login_status = $this->checkLoginStatus();
        if ( $login_status == true ) {
            $member_level = $this->memberInfo->member_level;
        } else {
            $member_level = 0;
        }

        $model = new service_Goods_mobile();
        $model->setGoods_id( $goods_id );
        $model->setMember_level( $member_level );
        $goods_info = $model->getGoodsInfo();

        if ( !$goods_info ) {
            parent::no( '商品不存在！' );
        } else {
            if ( empty( $goods_info->is_supplier ) ) {
                parent::no( '商品不是云端商品库产品' );
            }
            if ( $goods_info->is_delete == 1 ) {
                parent::no( '商品已经删除' );
            }
        }
        //兑换类 商品。（兑换红包|兑换购物券）不能访问购买页面|（兑换商品）才能访问
        if ( $goods_info->goods_type == service_Goods_base::goods_type_exchange && $goods_info->goods_class <> service_Goods_base::goods_class_exchange_goods ) {
            parent::no( '亲，出错了。您访问的是非兑换商品' );
        }
        $goods_info->goods_image_id = $this->getImage( $goods_info->goods_image_id, '640x0', 'goods' );
        $goods_info->goods_desc = preg_replace( '/(width|height)="(\d+)"/', '', $goods_info->goods_desc );
        $goods_info->goods_desc = str_replace( 'width: 750px;', '', $goods_info->goods_desc );
        $item_info = $model->getItemId( $goods_info );
        $goods_info->item_id = $item_info->item_id;

        $goods_spec_array = $model->getGoodsSpecArray();
        $goods_sku_array = $model->getGoodsSkuArray();

        $goods_image_array = $model->getGoodsImageArray( $goods_id );

        if ( $goods_info->goods_type == service_Goods_base::goods_type_exchange && $goods_info->goods_class == service_Goods_base::goods_class_exchange_goods ) {
            $exchange_goods = true;
            $buy_now_button = '立即抽奖';
        } else {
            $exchange_goods = false;
            $buy_now_button = '立即购买';
        }

        //商品规格
        $array[ 'goods_spec_array' ] = json_encode( $goods_spec_array[ 'result_object' ], true );
        //商品规格对应的sku 价格/库存
        $array[ 'goods_sku_array' ] = json_encode( $goods_sku_array, true );
        $array[ 'goods_info' ] = $goods_info;
        $array[ 'goods_image_array' ] = $goods_image_array;
        $array[ 'exchange_goods' ] = $exchange_goods;
        $array[ 'buy_now_button' ] = $buy_now_button;

//           echo '<pre>';
//           print_r( $array );
//         echo "</pre>";
//        die;

        $this->assign( $array );
        if ( $exchange_goods ) {
            $this->V( 'goods_exchange' );
        } else {
            $this->V( 'goods' );
        }
    }

    /**
     * 根据体质标签取体质标签的商品
     */
    public function getSurveyGoodsArray()
    {
        $label_id = Input::get( 'label_id', 0 )->required( '体质标签不能为空' )->int();

        if ( Filter::getStatus() === false ) {
            throw new ApiException( Filter::getFailMessage() ); //会返回上面参数接收时第一个失败的required里的错误内容，或格式失败的内容
        }
        $survey_model = new SurveyQuestion();

        $result = $survey_model->getRecommendGoodsListBySurvey( $label_id );

        $this->apiReturn( $result );

    }

}
