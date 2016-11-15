<?php


    $txtfilePath   = __DIR__.'/files/textfile.txt';
    $txtfileStream = fopen($txtfilePath, 'r+');
    $txtfileStats  = fstat($txtfileStream);

    $imgfilePath   = __DIR__.'/files/imgfile.gif';
    $imgfileStream = fopen($imgfilePath, 'r+');
    $imgfileStats  = fstat($imgfileStream);

    $zipfilePath   = __DIR__.'/files/zipfile.zip';
    $zipfileStream = fopen($zipfilePath, 'r+');
    $zipfileStats  = fstat($zipfileStream);

    return [

        // Ordered list
        'ordered' => [
            'multiple' => [
                0 => [
                    'name'      => basename($txtfilePath),
                    'type'      => mime_content_type($txtfilePath),
                    'tmp_name'  => $txtfilePath,
                    'error'     => UPLOAD_ERR_OK,
                    'size'      => $txtfileStats['size']
                ],
                1 => [
                    'name'      => basename($imgfilePath),
                    'type'      => mime_content_type($imgfilePath),
                    'tmp_name'  => $imgfilePath,
                    'error'     => UPLOAD_ERR_INI_SIZE,
                    'size'      => $imgfileStats['size']
                ]
            ],
            'single' => [
                'name'      => basename($zipfilePath),
                'type'      => mime_content_type($zipfilePath),
                'tmp_name'  => $zipfilePath,
                'error'     => UPLOAD_ERR_FORM_SIZE,
                'size'      => $zipfileStats['size']
            ]
        ],

        // Unordered files list
        'unordered' => [
            'multiple' => [
                'name'      => [
                    0 => basename($txtfilePath),
                    1 => basename($imgfilePath),
                ],
                'type'  => [
                    0 => mime_content_type($txtfilePath),
                    1 => mime_content_type($imgfilePath)
                ],
                'tmp_name'  => [
                    0 => $txtfilePath,
                    1 => $imgfilePath,
                ],
                'error'     => [
                    0 => UPLOAD_ERR_OK,
                    1 => UPLOAD_ERR_INI_SIZE,
                ],
                'size'      => [
                    0 => $txtfileStats['size'],
                    1 => $imgfileStats['size']
                ]
            ],
            'single' => [
                'name'      => basename($zipfilePath),
                'type'      => mime_content_type($zipfilePath),
                'tmp_name'  => $zipfilePath,
                'error'     => UPLOAD_ERR_FORM_SIZE,
                'size'      => $zipfileStats['size']
            ]
        ]

    ];
