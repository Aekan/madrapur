<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */
namespace backend\assets;

use common\assets\AdminLte;
use common\assets\Html5shiv;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class BackendAsset extends AssetBundle {

    /**
     *
     * @var string
     */
    public $basePath = '@webroot';

    /**
     *
     * @var string
     */
    public $baseUrl = '@web';

    /**
     *
     * @var array
     */
    public $css = [
        'css/style.css',
        'css/app-style.css',
    ];

    /**
     *
     * @var array
     */
    public $js = [
        'js/app.js',
        'js/1-script.js',
        'js/2-script.js',
        'js/3-script.js',
        'js/app-script.js',
    ];

    /**
     *
     * @var array
     */
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

    /**
     *
     * @var array
     */
    public $depends = [
        YiiAsset::class,
        AdminLte::class,
        Html5shiv::class
    ];
}
