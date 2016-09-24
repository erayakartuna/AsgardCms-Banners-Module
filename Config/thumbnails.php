<?php

return [
    'bannerThumb' => [
        'fit' => [
            'width' => '1900',
            'height' => '855',
            'callback' => function ($constraint) {
                $constraint->upsize();
            }
        ],
    ]
];
