<?php
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img(Yii::$app->request->baseUrl . '/images/icon2.png', ['alt' => 'logo', 'class' => 'custom-icon']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            
             'class' => 'navbar-dark bg-dark navbar-expand-md',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => 'Dashboard', 'url' => ['/site/dashboard']];
        $menuItems[] = ['label' => 'Logout (' . Yii::$app->user->identity->username . ')', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <?php
    
        $service = isset($this->params['service']) ? $this->params['service'] : Yii::$app->request->get('service');
        $bodyClass = "";
        $textClass = "";

        switch(strtolower($service)) {
            case 'm-pesa':
                $bodyClass = 'mpesa-bg';
                $textClass = 'mpesa-text';
                break;

            case 'halopesa':
                $bodyClass = 'halopesa-bg';
                $textClass = 'halopesa-text';
                break;

            case 'mix-by-yas':
                $bodyClass = 'mix-bg';
                $textClass = 'mix-text';
                break;

            case 'airtel-money':
                $bodyClass = 'airtel-bg';
                $textClass = 'airtel-text';
                break;

            default:
                $bodyClass = 'default-bg';
                $textClass = 'default-text';
        }
        ?>

    <div class="container <?= $bodyClass ?> <?= $textClass ?>">
        
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="float-left"><img src="<?= Yii::$app->request->baseUrl ?>/images/icon2.png" class="custom-icon" alt="logo" /></p>
    </div>
</footer>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>