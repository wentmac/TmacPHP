<?php

/**
 * api 会员账户 管理模块 Model
 * ============================================================================
 * TBlog TBlog博客系统　BY Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Trade.class.php 887 2017-02-10 16:04:29Z zhangwentao $
 * http://shop.weixinshow.com；
 */

namespace mobile\service\survey;

use \dao_factory_base;
use \service_Model_base;
use \Tmac;

class SurveyQuestion extends service_Model_base
{

    ####################################################################### baoshencha project start
    const man = 1;
    const women = 2;

    /**
     * 我的性别-单选
     */
    const question_sex = 2;
    /**
     * 我的体形-单选
     */
    const question_body_shape = 3;
    /**
     * 我的舌象
     */
    const question_tongue = 4;
    /**
     * 精神面貌
     */
    const question_vigor = 5;
    /**
     * 一年内感冒情况
     */
    const question_cold_situation = 6;
    /**
     * 皮肤情况
     */
    const question_skin_situation = 7;
    /**
     * 对寒热的感觉
     */
    const question_feeling_cold_heat = 8;
    /**
     * 口中感觉
     */
    const question_feeling_in_the_mouth = 9;
    /**
     * 大便情况
     */
    const question_stool_condition = 10;
    /**
     * 女性生理情况
     */
    const question_female_physiological_condition = 11;
    /**
     * 男性生理情况
     */
    const question_male_physiological_condition = 12;
    /**
     * 过敏情况
     */
    const question_allergic_condition = 13;
    /**
     *
     * 下面两种情况您更关心哪一个
     */
    const question_physique_condition_confirm = 14;
    /**
     * 体质标签
     */
    const question_physique_condition = 15;


    ############################## 标签常量
    /**
     * 气虚
     */
    const label_qixu = 1;
    /**
     * 阳虚
     */
    const label_yangxu = 2;
    /**
     * 阴虚
     */
    const label_yinxu = 3;
    /**
     * 痰湿
     */
    const label_tanshi = 4;
    /**
     * 湿热
     */
    const label_shire = 5;
    /**
     * 血瘀
     */
    const label_xueyu = 6;
    /**
     * 气郁
     */
    const label_qiyu = 7;
    /**
     * 特禀
     */
    const label_tebing = 8;
    /**
     * 平和
     */
    const label_pinghe = 9;


    ####################################################################### baoshencha project end

    /**
     * 问卷完成状态
     * 未完成
     */
    const is_completed_no = 0;
    /**
     * 问卷完成状态
     * 完成
     */
    const is_completed_yes = 1;

    use \base\core\traits\ErrorMessage;

    protected $memberSettingInfo;

    /**
     * @param mixed $memberSettingInfo
     */
    public function setMemberSettingInfo( $memberSettingInfo )
    {
        $this->memberSettingInfo = $memberSettingInfo;
    }


    /**
     * @var array
     * 问卷问题得分的规则
     */
    static public $survey_question_score = array(
        self::question_body_shape => [
            1 => [
                self::man => [ 'label' => self::label_yangxu, 'score' => 10 ],
                self::women => [ 'label' => self::label_yangxu, 'score' => 10 ]
            ],//均匀胖
            2 => [
                self::man => [ 'label' => self::label_tanshi, 'score' => 30 ],
                self::women => [ 'label' => self::label_tanshi, 'score' => 40 ]
            ],//游泳圈
            3 => [
                self::man => [ 'label' => self::label_pinghe, 'score' => 20 ],
                self::women => [ 'label' => self::label_pinghe, 'score' => 20 ]
            ],//正常
            4 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 10 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 10 ]
            ]//瘦
        ],
        self::question_tongue => [
            1 => [
                self::man => [ 'label' => self::label_pinghe, 'score' => 20 ],
                self::women => [ 'label' => self::label_pinghe, 'score' => 20 ]
            ],//淡红舌薄白苔
            2 => [
                self::man => [ 'label' => self::label_qixu, 'score' => 10 ],
                self::women => [ 'label' => self::label_qixu, 'score' => 10 ]
            ],//舌胖大有齿痕
            3 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 10 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 10 ]
            ],//少苔或无苔
            4 => [
                self::man => [ 'label' => self::label_shire, 'score' => 10 ],
                self::women => [ 'label' => self::label_shire, 'score' => 10 ]
            ]//苔黄厚腻
        ],
        self::question_vigor => [
            1 => [
                self::man => [ 'label' => self::label_pinghe, 'score' => 30 ],
                self::women => [ 'label' => self::label_pinghe, 'score' => 30 ]
            ],//精力充沛
            2 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 10 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 10 ]
            ],//急躁易怒
            3 => [
                self::man => [ 'label' => self::label_qixu, 'score' => 50 ],
                self::women => [ 'label' => self::label_qixu, 'score' => 50 ]
            ],//容易疲乏
            4 => [
                self::man => [ 'label' => self::label_qiyu, 'score' => 80 ],
                self::women => [ 'label' => self::label_qiyu, 'score' => 60 ]
            ]//爱生闷气
        ],
        self::question_cold_situation => [
            1 => [
                self::man => [ 'label' => self::label_pinghe, 'score' => 30 ],
                self::women => [ 'label' => self::label_pinghe, 'score' => 30 ]
            ],//<=2次
            2 => [
                self::man => [ 'label' => self::label_qixu, 'score' => 20 ],
                self::women => [ 'label' => self::label_qixu, 'score' => 20 ]
            ],//>3次
            3 => [
                self::man => [ 'label' => self::label_tebing, 'score' => 30 ],
                self::women => [ 'label' => self::label_tebing, 'score' => 30 ]
            ],//没有感冒也会鼻痒、流鼻涕
            4 => [
                self::man => [ 'label' => self::label_tebing, 'score' => 20 ],
                self::women => [ 'label' => self::label_tebing, 'score' => 20 ]
            ]//环境变化会导致喘促
        ],
        self::question_skin_situation => [
            1 => [
                self::man => [ 'label' => self::label_tanshi, 'score' => 20 ],
                self::women => [ 'label' => self::label_tanshi, 'score' => 30 ]
            ],//额部出油多
            2 => [
                self::man => [ 'label' => self::label_shire, 'score' => 30 ],
                self::women => [ 'label' => self::label_shire, 'score' => 30 ]
            ],//经常长痘
            3 => [
                self::man => [ 'label' => self::label_xueyu, 'score' => 50 ],
                self::women => [ 'label' => self::label_xueyu, 'score' => 50 ]
            ],//色斑暗沉
            4 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 20 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 20 ]
            ]//皮肤干燥
        ],
        self::question_feeling_cold_heat => [
            1 => [
                self::man => [ 'label' => self::label_yangxu, 'score' => 30 ],
                self::women => [ 'label' => self::label_yangxu, 'score' => 30 ]
            ],//手脚易凉
            2 => [
                self::man => [ 'label' => self::label_yangxu, 'score' => 30 ],
                self::women => [ 'label' => self::label_yangxu, 'score' => 30 ]
            ],//怕吃生冷
            3 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 20 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 20 ]
            ],//手脚心易热
            4 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 10 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 10 ]
            ]//喜食冷饮
        ],
        self::question_feeling_in_the_mouth => [
            1 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 20 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 20 ]
            ],//咽干口燥
            2 => [
                self::man => [ 'label' => self::label_tanshi, 'score' => 20 ],
                self::women => [ 'label' => self::label_tanshi, 'score' => 30 ]
            ],//口舌粘腻
            3 => [
                self::man => [ 'label' => self::label_qiyu, 'score' => 20 ],
                self::women => [ 'label' => self::label_qiyu, 'score' => 10 ]
            ],//口中苦味
            4 => [
                self::man => [ 'label' => self::label_shire, 'score' => 20 ],
                self::women => [ 'label' => self::label_shire, 'score' => 20 ]
            ]//口臭
        ],
        self::question_stool_condition => [
            1 => [
                self::man => [ 'label' => self::label_shire, 'score' => 20 ],
                self::women => [ 'label' => self::label_shire, 'score' => 20 ]
            ],//粘马桶
            2 => [
                self::man => [ 'label' => self::label_yangxu, 'score' => 20 ],
                self::women => [ 'label' => self::label_yangxu, 'score' => 20 ]
            ],//常拉肚子
            3 => [
                self::man => [ 'label' => self::label_yinxu, 'score' => 20 ],
                self::women => [ 'label' => self::label_yinxu, 'score' => 20 ]
            ],//大便干燥
            4 => [
                self::man => [ 'label' => self::label_qixu, 'score' => 20 ],
                self::women => [ 'label' => self::label_qixu, 'score' => 20 ]
            ]//排便费劲
        ],
        self::question_female_physiological_condition => [
            1 => [
                self::man => [ 'label' => self::label_qiyu, 'score' => 0 ],
                self::women => [ 'label' => self::label_qiyu, 'score' => 20 ]
            ],//非经期胁肋或乳房胀痛
            2 => [
                self::man => [ 'label' => self::label_shire, 'score' => 0 ],
                self::women => [ 'label' => self::label_shire, 'score' => 20 ]
            ],//白带多、色黄
            3 => [
                self::man => [ 'label' => self::label_xueyu, 'score' => 0 ],
                self::women => [ 'label' => self::label_xueyu, 'score' => 50 ]
            ],//痛经、月经色暗、血块多
            4 => [
                self::man => [ 'label' => self::label_qiyu, 'score' => 0 ],
                self::women => [ 'label' => self::label_qiyu, 'score' => 10 ]
            ]//入睡困难或易醒
        ],
        self::question_male_physiological_condition => [
            1 => [
                self::man => [ 'label' => self::label_yangxu, 'score' => 10 ],
                self::women => [ 'label' => self::label_yangxu, 'score' => 0 ]
            ],//喜欢安静不喜欢说话
            2 => [
                self::man => [ 'label' => self::label_shire, 'score' => 20 ],
                self::women => [ 'label' => self::label_shire, 'score' => 0 ]
            ],//头发油、脱发、秃顶
            3 => [
                self::man => [ 'label' => self::label_xueyu, 'score' => 50 ],
                self::women => [ 'label' => self::label_xueyu, 'score' => 0 ]
            ],//容易忘事、记性不好
            4 => [
                self::man => [ 'label' => self::label_tanshi, 'score' => 30 ],
                self::women => [ 'label' => self::label_tanshi, 'score' => 0 ]
            ]//睡觉打呼噜
        ],
        self::question_allergic_condition => [
            1 => [
                self::man => [ 'label' => self::label_tebing, 'score' => 60 ],
                self::women => [ 'label' => self::label_tebing, 'score' => 60 ]
            ],//食物过敏
            2 => [
                self::man => [ 'label' => self::label_tebing, 'score' => 60 ],
                self::women => [ 'label' => self::label_tebing, 'score' => 60 ]
            ],//异味或异物吸入过敏
            3 => [
                self::man => [ 'label' => self::label_tebing, 'score' => 60 ],
                self::women => [ 'label' => self::label_tebing, 'score' => 60 ]
            ],//有荨麻疹
            4 => [
                self::man => [ 'label' => self::label_tebing, 'score' => 60 ],
                self::women => [ 'label' => self::label_tebing, 'score' => 60 ]
            ]//皮肤容易有抓痕
        ]
    );


    private $uid;
    private $memberInfo;
    private $survey_field;
    private $survey_result;

    //已存在的问卷详情
    private $source_survey_result;
    private $survey_result_array;

    /**
     * @param mixed $uid
     */
    public function setUid( $uid )
    {
        $this->uid = $uid;
    }

    /**
     * @param mixed $memberInfo
     */
    public function setMemberInfo( $memberInfo )
    {
        $this->memberInfo = $memberInfo;
        return $this;
    }

    /**
     * @param mixed $survey_field
     */
    public function setSurveyField( $survey_field )
    {
        $this->survey_field = $survey_field;
        return $this;
    }

    /**
     * @param mixed $survey_result
     */
    public function setSurveyResult( $survey_result )
    {
        $this->survey_result = $survey_result;
        return $this;
    }


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 更新
     * @param $survey_id
     * @param \base\entity\Survey $entity_Survey
     * @return bool
     */
    public function updateSurveyById( $survey_id, \base\entity\Survey $entity_Survey )
    {
        $survey_dao = \dao_factory_base::getSurveyDao();
        $survey_dao->setPk( $survey_id );
        $survey_dao->updateByPk( $entity_Survey );
        return true;
    }

    /**
     * 检测是否有未完成的问卷
     */
    public function checkUnFinishedSurvey()
    {
        $dao = \dao_factory_base::getSurveyDao();
        $where = "uid={$this->memberInfo->uid} AND is_completed=" . self::is_completed_no;
        $dao->setWhere( $where );
        $dao->setOrderby( 'survey_id DESC' );
        $dao->setLimit( 1 );
        $dao->setField( '*' );
        $res = $this->source_survey_result = $dao->getInfoByWhere();
        if ( empty( $res ) ) {
            return true;
        }
        return $res;
    }


    /**
     * 处理得分
     * @param \base\entity\Survey $entity_Survey
     */
    public function handleSurveyScore( \base\entity\Survey $entity_Survey )
    {
        $step_id_field_array = Tmac::config( 'survey.step_id_field' );
        //如果是性别选项，不计算得分，直接退出
        if ( $entity_Survey->step_id == self::question_sex ) {
            $entity_Survey->{$this->survey_field} = $this->survey_result;
            $entity_Survey->score = 0;
            $entity_Survey->score_detail = '';
            return true;
        } else {
            //其他的正常问卷
            if ( is_array( $this->survey_result ) ) {
                $survey_result_res = json_encode( $this->survey_result );
            } else {
                $survey_result_res = $this->survey_result;
            }
            $entity_Survey->{$this->survey_field} = $survey_result_res;
            $this->source_survey_result->{$this->survey_field} = $survey_result_res; //原问卷记录的当前问题字段也更新下来
            //todo 先清空所有后面的回答及积分
            foreach ( $step_id_field_array as $step_id => $field ) {
                if ( $step_id <= $entity_Survey->step_id ) {
                    continue;
                }
                $entity_Survey->{$field} = '';
            }
            $entity_Survey->score = 0;
            $entity_Survey->score_detail = '';
        }

        if ( $entity_Survey->step_id == self::question_allergic_condition ) {
            //如果是过敏情况。最后一个问卷提交答案
            //todo 重新计算所有的积分。为了效率可以最后一步再计算积分
            $this->survey_result_array = [];
            foreach ( $step_id_field_array as $step_id => $field ) {
                if ( $step_id == self::question_sex
                    || $step_id == self::question_physique_condition_confirm
                    || $step_id == self::question_physique_condition ) {
                    continue;
                }
                if ( !empty( $this->source_survey_result->{$field} ) ) { //已存在问卷答题结果
                    $survey_result_array = json_decode( $this->source_survey_result->{$field}, true );
                    $this->handleSurveyResultScore( $step_id, $survey_result_array, $entity_Survey );
                }
            }

            $entity_Survey->score_detail = json_encode( $this->survey_result_array );
            arsort( $this->survey_result_array );//根据值，以降序对关联数组进行排序   .最高分->最低分排序
            $entity_Survey->score = current( $this->survey_result_array );
        }
        return true;
    }

    /**
     * 处理问卷回答的结果标签的分数
     *
     */
    private function handleSurveyResultScore( $step_id, $survey_result, \base\entity\Survey $entity_Survey )
    {
        if ( is_array( $survey_result ) ) {
            foreach ( $survey_result as $survey ) {
                $this->handleLabelScore( $survey, $step_id );
            }
        } else {
            $this->handleLabelScore( $survey_result, $step_id );
        }
    }

    /**
     * 处理 体质标签的得分
     * @param $survey_result
     * @param \base\entity\Survey $entity_Survey
     * @return array
     */
    private function handleLabelScore( $survey_result, $step_id )
    {
        if ( empty( self::$survey_question_score[ $step_id ][ $survey_result ][ $this->source_survey_result->sex ] ) ) {
            return $this->survey_result_array;
        } else {
            $survey_label_array = self::$survey_question_score[ $step_id ][ $survey_result ][ $this->source_survey_result->sex ];
        }

        $label = $survey_label_array[ 'label' ];
        if ( empty( $this->survey_result_array[ $label ] ) ) { //体质标签 尚未有得分。初始化
            $this->survey_result_array[ $label ] = $survey_label_array[ 'score' ];
        } else { //体质标签 已经有得分 累加
            $this->survey_result_array[ $label ] += $survey_label_array[ 'score' ];
        }
        return $this->survey_result_array;
    }

    /**
     * 更新问卷项目
     * @param \base\entity\Survey $entity_Survey
     * @return bool
     */
    public function updateSurvey( \base\entity\Survey $entity_Survey )
    {

        $check_un_completed = $this->checkUnFinishedSurvey();

        //处理问卷的选项得分
        $this->handleSurveyScore( $entity_Survey );
        $survey_dao = \dao_factory_base::getSurveyDao();


        if ( $check_un_completed === true ) {
            //没有未完成的，需要人工插入新的
            $survey_id = $survey_dao->insert( $entity_Survey );
            if ( !$survey_id ) {
                $this->setErrorMessage( '更新写入问卷失败，请联系管理员' );
                return false;
            }
            return true;
        }
        $entity_Survey->survey_id = $check_un_completed->survey_id;
        //$entity_Survey_base->current_fh_currency = new \TmacDbExpr( 'current_fh_currency-' . $entity_Survey->fh_currency_payment );

        // question_allergic_condition 过敏体质 如果分数不相同，就算出得分
        // 如果分数相同就出现   question_physique_condition_confirm 更关心哪个

        //更新卖家的金钱 商品供应商UID
        $survey_dao->setPk( $entity_Survey->survey_id );
        $survey_dao->updateByPk( $entity_Survey );

        //返回下一个问卷ID
        if ( $entity_Survey->step_id == self::question_stool_condition ) {
            if ( $check_un_completed->sex == SurveyQuestion::man ) {
                //如果是男生  不出现 女性生理情况-多选
                $next_step_id = SurveyQuestion::question_male_physiological_condition;
            } else if ( $check_un_completed->sex == SurveyQuestion::women ) {
                //如果是女生  不出现 男性生理情况-多选
                $next_step_id = SurveyQuestion::question_female_physiological_condition;
            }
        } else if ( $entity_Survey->step_id == self::question_female_physiological_condition || $entity_Survey->step_id == self::question_male_physiological_condition ) {
            $next_step_id = SurveyQuestion::question_allergic_condition;
        } else {
            $next_step_id = $entity_Survey->step_id + 1;
        }
        return $next_step_id;
    }

    /**
     * 取所有店铺的所有商品
     * $this->uid;
     * $this->getRecommendGoodsListBySurvey($label_id);
     */
    public function getRecommendGoodsListBySurvey( $label_id )
    {
        $dao = dao_factory_base::getGoodsDao();
        $where = 'uid=' . \service_Member_base::yph_uid;
        $where .= ' AND ' . $dao->getWhereInStatement( 'goods_class', [ $label_id ] );
        $where .= " AND is_delete=0";

        $dao->setWhere( $where );
        $res = [];
        $dao->setOrderby( 'goods_sort DESC,goods_id DESC' );
        $dao->setField( 'goods_id,goods_name,goods_price,goods_cat_id,goods_image_id,sales_volume,goods_type,goods_class' );
        $res = $dao->getListByWhere();


        $return_array = $goods_cat_array = $item_id_array = $goods_id_array = [];
        if ( !empty( $res ) ) {
            $goods_cat_id_array = [];
            foreach ( $res as $value ) {
                $value->goods_image_url = $this->getImage( $value->goods_image_id, '300', 'goods' );
                $goods_cat_id_array[] = $value->goods_cat_id;

                $value->item_id = 0;

                $return_array[ $value->goods_cat_id ][] = $value;
                $goods_id_array[] = $value->goods_id;
            }

            if ( !empty( $goods_cat_id_array ) ) {
                $goods_cat_array = $this->getCategoryArrayByCatIdArray( array_unique( $goods_cat_id_array ) );
            }

            if ( !empty( $goods_id_array ) ) {
                $goods_item_id_map = $this->getItemIdArrayByGoodsIdArray( $goods_id_array );
                foreach ( $res as $value ) {
                    $value->item_id = empty($goods_item_id_map[$value->goods_id]) ? 0 : $goods_item_id_map[$value->goods_id];
                    $item_id_array[ $value->item_id ] = 1;
                }
            }
        }

        return [
            'return_array' => $return_array,
            'goods_cat_array' => $goods_cat_array,
            'item_id_array' => $item_id_array
        ];
    }


    /**
     * 取商品分类名数据
     * @param type $cat_id_string
     * @return type
     */
    private function getCategoryArrayByCatIdArray( $cat_id_array )
    {
        $dao = dao_factory_base::getGoodsCategoryDao();
        $dao->setField( 'goods_cat_id,cat_name' );
        $where = $dao->getWhereInStatement( 'goods_cat_id', $cat_id_array );
        $dao->setWhere( $where );
        $res = $dao->getListByWhere();

        $category_array = [];
        if ( $res ) {
            foreach ( $res as $value ) {
                $category_array[ $value->goods_cat_id ] = $value->cat_name;
            }
        }
        return $category_array;
    }

    private function getItemIdArrayByGoodsIdArray( $goods_id_array )
    {
        $dao = dao_factory_base::getItemDao();
        $dao->setField( 'item_id,goods_id' );
        $where = $dao->getWhereInStatement( 'goods_id', $goods_id_array );
        $where .= " AND is_self=1 AND is_delete=0";
        $dao->setWhere( $where );
        $res = $dao->getListByWhere();

        $result = [];
        if ( $res ) {
            foreach ( $res as $value ) {
                $result[ $value->goods_id ] = $value->item_id;
            }
        }
        return $result;
    }
}
