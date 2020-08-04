<?php

namespace ccheng\task\backend\assets;

use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 *
 * @since 0.1
 */
class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $css = [
        'css/adminlte.css',
    ];
    public $js = [
        'js/adminlte.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
        'yii\web\YiiAsset',
        BootstrapAsset::class
    ];

    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = false;
}