<?php
use yii\helpers\Html;
?>
<div class="site-dashboard">
    <h1>Welcome, <?= Html::encode(Yii::$app->user->identity->username) ?>!</h1>
    <div class="profile-picture text-center">
         <?= Html::a('profile', ['site/profile'], ['class' => 'btn btn-primary']) ?>
           
    </div>
    <h2 class="text-center mt-4">
    
     </h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center about">
                <div class="card-body">
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/about.png" class="custom-icon" alt="logo" />
                    <!-- <h5 class="card-title">About</h5> -->
                    <p class="card-text">Learn about Transactions Tracker.</p>
                    <?= Html::a(' About', ['site/about'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mpesa">
                <div class="card-body">
                    <!-- <i class="fas fa-money-bill-wave fa-icon"></i> -->
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/m-pesa.png" class="custom-icon" alt="logo" />
                    <!-- <p class="card-text">Manage your M-Pesa transactions.</p> -->
                    <?= Html::a('M-Pesa', ['transaction/mpesa'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center halopesa">
                <div class="card-body">
                    <!-- <i class="fas fa-mobile-alt fa-icon"></i> -->
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/halo.png" class="custom-icon" alt="logo" />
                    <!-- <p class="card-text">Manage your Halopesa transactions.</p> -->
                    <?= Html::a('Halopesa', ['transaction/halopesa'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mix-by-yas">
                <div class="card-body">
                    <!-- <i class="fas fa-coins fa-icon"></i>
                    <h5 class="card-title">Mix by Yas</h5> -->
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/mixx.png" class="custom-icon" alt="logo" />
                    <!-- <p class="card-text">Manage your Mix by Yas transactions.</p> -->
                    <?= Html::a(' Mix by Yas', ['transaction/mix-by-yas'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center airtel-money">
                <div class="card-body">
                    <!-- <i class="fas fa-wallet fa-icon"></i>
                    <h5 class="card-title">Airtel Money</h5> -->
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/airtel.png" class="custom-icon" alt="logo" />
                    <!-- <p class="card-text">Manage your Airtel Money transactions.</p> -->
                    <?= Html::a('Airtel Money', ['transaction/airtel-money'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center logout">
                <div class="card-body">
                    <!-- <i class="fas fa-sign-out-alt fa-icon"></i>
                    <h5 class="card-title">Logout</h5> -->
                     <img src="<?= Yii::$app->request->baseUrl ?>/images/logout1.png" class="custom-icon" alt="logo" />
                    <?= Html::a('Logout', ['site/logout'], ['class' => 'btn btn-danger', 'data-method' => 'post']) ?>
                </div>
            </div>
        </div>
    </div>
</div>