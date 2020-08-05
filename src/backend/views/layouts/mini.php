<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

ccheng\task\backend\assets\AdminLteAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue" style="background-color:#ecf0f5">

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?= $this->title ?>
                </h1>

                <?= Breadcrumbs::widget([
                    'tag' => 'ol',
                    'homeLink' => [
                        'label' => '<i class="fa fa-dashboard"></i>' . Yii::$app->params['adminAcronym'],
                        'url' => "",
                    ],
                    'encodeLabels' => false,
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Content Header (Page header) -->
                <?php $this->beginBody() ?>
                <?php echo $content ?>
                <?php $this->endBody() ?>
            </section>

    </body>
    </html>
<?php $this->endPage() ?>