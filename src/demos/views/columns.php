<?php

use ethercap\apiBase\demos\models\Project;

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
        },
    ],
    'widgetColumn' => [
        'class' => \ethercap\apiBase\columns\WidgetColumn::class,
        'widgetConfig' => [
            'class' => \ethercap\apiBase\widgets\ModelsApi::class,
            'builder' => $res,
            'models' => Project::findAll(['id' => ['143', '257']]),
            'columns' => [
                'id', 'title',
            ],
        ],
        'label' => '顾问信息',
    ],
];
