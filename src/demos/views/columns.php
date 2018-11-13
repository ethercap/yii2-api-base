<?php

use yii\helpers\ArrayHelper;

return [
    'id',
    'title',
    'vendorId',
    'logoUrl',
    'viewCount',
    'meetingCount',
    'interestedCount',
    'vendorName' => [
        'attribute' => 'vendorId',
        'value' => function ($model) {
            $vendor = $model->vendor;
            return $vendor ? $vendor->name : '无';
        }, ],
    'agents' => [
        'label' => '顾问信息',
        'value' => function ($model) {
            $agentNames = [];
            foreach ($model->agents as $agent) {
                if (!$agent->agent) {
                    continue;
                }
                $agentNames[] = ArrayHelper::getValue($agent, 'realAgentInfo.name', '未知用户');
            }
            return $agentNames;
        },
    ],
];
