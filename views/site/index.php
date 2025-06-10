<?php
use yii\helpers\Html;
?>
<div class="site-index">
    <img src="<?= Yii::$app->request->baseUrl ?>/images/icon.png" class="custom-icon" alt="logo" />
    <h1> TRANSACTIONS TRACKER</h1>
    <p>Please <?= Html::a('Sign Up', ['site/signup']) ?> 
    or 
    <?= Html::a('Login', ['site/login']) ?> 
    to continue.</p>
</div>