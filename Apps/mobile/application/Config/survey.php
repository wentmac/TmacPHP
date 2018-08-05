<?php
/**
 * Created by PhpStorm.
 * User: zhangwentao
 * Date: 2018/7/4
 * Time: 17:13
 */


$config[ 'step_id_field' ] = [
    '2' => 'sex',
    '3' => 'body_shape',
    '4' => 'tongue',
    '5' => 'vigor',
    '6' => 'cold_situation',
    '7' => 'skin_situation',
    '8' => 'feeling_cold_heat',
    '9' => 'feeling_in_the_mouth',
    '10' => 'stool_condition',
    '11' => 'female_physiological_condition',
    '12' => 'male_physiological_condition',
    '13' => 'allergic_condition',
    '14' => 'physique_type_condition',
    '15' => 'physique_condition'
];

$config[ 'sex' ] = array(
    'name' => '我的性别-单选',
    'type' => 'radio',
    'step_id' => '2',
    'object' => [
        [ 'name' => '男', 'image_name' => 'sex_man', 'value' => 1 ],
        [ 'name' => '女', 'image_name' => 'sex_women', 'value' => 2 ]
    ]
);

$config[ 'body_shape' ] = array(
    'name' => '我的体形-单选',
    'type' => 'radio',
    'step_id' => '3',
    'object' => [
        [ 'name' => '均匀胖', 'image_name' => 'small_fat', 'value' => 1 ],
        [ 'name' => '游泳圈', 'image_name' => 'big_fat', 'value' => 2 ],
        [ 'name' => '正常', 'image_name' => 'normal', 'value' => 3 ],
        [ 'name' => '瘦', 'image_name' => 'thin', 'value' => 4 ]
    ]
);

$config[ 'tongue' ] = array(
    'name' => '我的舌象-单选',
    'type' => 'radio',
    'step_id' => '4',
    'object' => [
        [ 'name' => '淡红舌薄白苔', 'image_name' => 'tongue_red', 'value' => 1 ],
        [ 'name' => '舌胖大有齿痕', 'image_name' => 'tongue_fat', 'value' => 2 ],
        [ 'name' => '少苔或无苔', 'image_name' => 'tongue_small', 'value' => 3 ],
        [ 'name' => '苔黄厚腻', 'image_name' => 'tongue_yellow', 'value' => 4 ]
    ]
);

$config[ 'vigor' ] = array(
    'name' => '精神面貌-多选',
    'type' => 'checkbox',
    'step_id' => '5',
    'object' => [
        [ 'name' => '精力充沛', 'image_name' => 'energy_full', 'value' => 1 ],
        [ 'name' => '急躁易怒', 'image_name' => 'irritable', 'value' => 2 ],
        [ 'name' => '容易疲乏', 'image_name' => 'easy_fatigue', 'value' => 3 ],
        [ 'name' => '爱生闷气', 'image_name' => 'sulking', 'value' => 4 ]
    ],
    'nothing_value' => 0
);

$config[ 'cold_situation' ] = array(
    'name' => '一年内感冒情况-多选',
    'type' => 'checkbox',
    'step_id' => '6',
    'object' => [
        [ 'name' => '<=2次', 'image_name' => 'less_than_2', 'value' => 1 ],
        [ 'name' => '>3次', 'image_name' => 'more_than_2', 'value' => 2 ],
        [ 'name' => '没有感冒也会鼻痒、流鼻涕', 'image_name' => 'runny_nose', 'value' => 3 ],
        [ 'name' => '环境变化会导致喘促', 'image_name' => 'asthma', 'value' => 4 ]
    ],
    'nothing_value' => 0
);

$config[ 'skin_situation' ] = array(
    'name' => '皮肤情况-多选',
    'type' => 'checkbox',
    'step_id' => '7',
    'object' => [
        [ 'name' => '额部出油多', 'image_name' => 'more_oil', 'value' => 1 ],
        [ 'name' => '经常长痘', 'image_name' => 'long_pox', 'value' => 2 ],
        [ 'name' => '色斑暗沉', 'image_name' => 'dark_spots', 'value' => 3 ],
        [ 'name' => '皮肤干燥', 'image_name' => 'dry_skin', 'value' => 4 ],
        [ 'name' => '没有以上情况', 'image_name' => 'nothing', 'value' => 5 ]
    ],
    'nothing_value' => 5
);


$config[ 'feeling_cold_heat' ] = array(
    'name' => '对寒热的感觉-多选',
    'type' => 'checkbox',
    'step_id' => '8',
    'object' => [
        [ 'name' => '手脚易凉', 'image_name' => 'easy_cool_hands_feet', 'value' => 1 ],
        [ 'name' => '怕吃生冷', 'image_name' => 'afraid_to_eat_cold', 'value' => 2 ],
        [ 'name' => '手脚心易热', 'image_name' => 'hands_and_feet_hot', 'value' => 3 ],
        [ 'name' => '喜食冷饮', 'image_name' => 'like_to_eat_cold_drinks', 'value' => 4 ],
        [ 'name' => '没有以上情况', 'image_name' => 'nothing', 'value' => 5 ]
    ],
    'nothing_value' => 5
);

$config[ 'feeling_in_the_mouth' ] = array(
    'name' => '口中感觉-多选',
    'type' => 'checkbox',
    'step_id' => '9',
    'object' => [
        [ 'name' => '咽干口燥', 'image_name' => 'dry_throat', 'value' => 1 ],
        [ 'name' => '口舌粘腻', 'image_name' => 'sticky_tongue', 'value' => 2 ],
        [ 'name' => '口中苦味', 'image_name' => 'bitter_taste_in_the_mouth', 'value' => 3 ],
        [ 'name' => '口臭', 'image_name' => 'bad_breath', 'value' => 4 ],
        [ 'name' => '没有以上情况', 'image_name' => 'nothing', 'value' => 5 ]
    ],
    'nothing_value' => 5
);

$config[ 'stool_condition' ] = array(
    'name' => '大便情况-多选',
    'type' => 'checkbox',
    'step_id' => '10',
    'object' => [
        [ 'name' => '粘马桶', 'image_name' => '', 'value' => 1 ],
        [ 'name' => '常拉肚子', 'image_name' => '', 'value' => 2 ],
        [ 'name' => '大便干燥', 'image_name' => '', 'value' => 3 ],
        [ 'name' => '排便费劲', 'image_name' => '', 'value' => 4 ],
        [ 'name' => '没有以上情况', 'image_name' => 'nothing', 'value' => 5 ]
    ],
    'nothing_value' => 5
);

$config[ 'female_physiological_condition' ] = array(
    'name' => '女性生理情况-多选',
    'type' => 'checkbox',
    'step_id' => '11',
    'object' => [
        [ 'name' => '非经期胁肋或乳房胀痛', 'image_name' => '', 'value' => 1 ],
        [ 'name' => '白带多、色黄', 'image_name' => '', 'value' => 2 ],
        [ 'name' => '痛经、月经色暗、血块多', 'image_name' => '', 'value' => 3 ],
        [ 'name' => '入睡困难或易醒', 'image_name' => '', 'value' => 4 ],
        [ 'name' => '没有以上情况', 'image_name' => 'nothing', 'value' => 5 ]
    ],
    'nothing_value' => 5
);

$config[ 'male_physiological_condition' ] = array(
    'name' => '男性生理情况-多选',
    'type' => 'checkbox',
    'step_id' => '12',
    'object' => [
        [ 'name' => '喜欢安静不喜欢说话', 'image_name' => '', 'value' => 1 ],
        [ 'name' => '头发油、脱发、秃顶', 'image_name' => '', 'value' => 2 ],
        [ 'name' => '容易忘事、记性不好', 'image_name' => '', 'value' => 3 ],
        [ 'name' => '睡觉打呼噜', 'image_name' => '', 'value' => 4 ],
        [ 'name' => '没有以上情况', 'image_name' => 'nothing', 'value' => 5 ]
    ],
    'nothing_value' => 5
);


$config[ 'allergic_condition' ] = array(
    'name' => '过敏情况-多选',
    'type' => 'checkbox',
    'step_id' => '13',
    'object' => [
        [ 'name' => '食物过敏', 'image_name' => '', 'value' => 1 ],
        [ 'name' => '异味或异物吸入过敏', 'image_name' => '', 'value' => 2 ],
        [ 'name' => '有荨麻疹', 'image_name' => '', 'value' => 3 ],
        [ 'name' => '皮肤容易有抓痕', 'image_name' => '', 'value' => 4 ],
        [ 'name' => '没有以上情况', 'image_name' => 'nothing', 'value' => 5 ]
    ],
    'nothing_value' => 5
);


$config[ 'physique_type_condition' ] = array(
    'name' => '下面两种情况您更关心哪一个？',
    'type' => 'radio',
    'step_id' => '14',
    'object' => [
        [ 'name' => '一口气上三楼就气喘吁吁', 'image_name' => '', 'value' => 1 ],//气虚体质
        [ 'name' => '一吃西瓜就拉肚子，不敢吃', 'image_name' => '', 'value' => 2 ]//阳虚体质
    ],
    'nothing_value' => 5
);


$config[ 'physique_condition' ] = array(
    'name' => '体质标签',
    'type' => 'radio',
    'step_id' => '15',
    'object' => [
        [ 'label' => '气虚', 'name' => '一口气上三楼就气喘吁吁', 'image_name' => '', 'value' => 1 ],//气虚体质
        [ 'label' => '阳虚', 'name' => '一吃西瓜就拉肚子，不敢吃', 'image_name' => '', 'value' => 2 ],//阳虚体质
        [ 'label' => '阴虚', 'name' => '经常口干口渴、想喝水', 'image_name' => '', 'value' => 3 ],//阳虚体质
        [ 'label' => '痰湿', 'name' => '肚子大得像怀孕，想减肥', 'image_name' => '', 'value' => 4 ],//阳虚体质
        [ 'label' => '湿热', 'name' => '头发一天不洗就油得不行', 'image_name' => '', 'value' => 5 ],//阳虚体质
        [ 'label' => '血瘀', 'name' => '身上有时有刺痛感', 'image_name' => '', 'value' => 6 ],//阳虚体质
        [ 'label' => '气郁', 'name' => '总觉得心里面堵得慌', 'image_name' => '', 'value' => 7 ],//阳虚体质
        [ 'label' => '特禀', 'name' => '有过敏的经历', 'image_name' => '', 'value' => 8 ],//阳虚体质
        [ 'label' => '平和', 'name' => '平和体质', 'image_name' => '', 'value' => 9 ]//平和质
    ],
    'nothing_value' => 0
);


$config[ 'physique_condition_result' ] = [
    1 => [ 'label' => '气虚', 'name' => '一口气上三楼就气喘吁吁', 'image_name' => '', 'value' => 1 ],//气虚体质
    2 => [ 'label' => '阳虚', 'name' => '一吃西瓜就拉肚子，不敢吃', 'image_name' => '', 'value' => 2 ],//阳虚体质
    3 => [ 'label' => '阴虚', 'name' => '经常口干口渴、想喝水', 'image_name' => '', 'value' => 3 ],//阳虚体质
    4 => [ 'label' => '痰湿', 'name' => '肚子大得像怀孕，想减肥', 'image_name' => '', 'value' => 4 ],//阳虚体质
    5 => [ 'label' => '湿热', 'name' => '头发一天不洗就油得不行', 'image_name' => '', 'value' => 5 ],//阳虚体质
    6 => [ 'label' => '血瘀', 'name' => '身上有时有刺痛感', 'image_name' => '', 'value' => 6 ],//阳虚体质
    7 => [ 'label' => '气郁', 'name' => '总觉得心里面堵得慌', 'image_name' => '', 'value' => 7 ],//阳虚体质
    8 => [ 'label' => '特禀', 'name' => '有过敏的经历', 'image_name' => '', 'value' => 8 ],//阳虚体质
    9 => [ 'label' => '平和', 'name' => '平和体质', 'image_name' => '', 'value' => 9 ]//平和质
];