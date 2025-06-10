<?php
use yii\helpers\Html;
?>
<div class="site-dashboard">
    <h1>Welcome, <?= Html::encode(Yii::$app->user->identity->username) ?>!</h1>
    <div class="profile-picture text-center">
        <?php if (Yii::$app->user->identity->profile_picture): ?>
            <img src="<?= Html::encode(Yii::$app->user->identity->profile_picture) ?>" alt="Profile Picture" style="max-width: 150px; border-radius: 50%;">
        <?php else: ?>
            <p>No profile picture set.</p>
        <?php endif; ?>
        <p><?= Html::a('Update Profile Picture', ['site/profile'], ['class' => 'btn btn-secondary mt-2']) ?></p>
    </div>
    <h2 class="text-center mt-4">Navigation</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center about">
                <div class="card-body">
                    <i class="fas fa-info-circle fa-icon"></i>
                    <h5 class="card-title">About</h5>
                    <p class="card-text">Learn more about Mobile Tracker.</p>
                    <?= Html::a('Go to About', ['site/about'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mpesa">
                <div class="card-body">
                    <i class="fas fa-money-bill-wave fa-icon"></i>
                    <h5 class="card-title">M-Pesa</h5>
                    <p class="card-text">Manage your M-Pesa transactions.</p>
                    <?= Html::a('Go to M-Pesa', ['transaction/mpesa'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center halopesa">
                <div class="card-body">
                    <i class="fas fa-mobile-alt fa-icon"></i>
                    <h5 class="card-title">Halopesa</h5>
                    <p class="card-text">Manage your Halopesa transactions.</p>
                    <?= Html::a('Go to Halopesa', ['transaction/halopesa'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mix-by-yas">
                <div class="card-body">
                    <i class="fas fa-coins fa-icon"></i>
                    <h5 class="card-title">Mix by Yas</h5>
                    <p class="card-text">Manage your Mix by Yas transactions.</p>
                    <?= Html::a('Go to Mix by Yas', ['transaction/mix-by-yas'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center airtel-money">
                <div class="card-body">
                    <i class="fas fa-wallet fa-icon"></i>
                    <h5 class="card-title">Airtel Money</h5>
                    <p class="card-text">Manage your Airtel Money transactions.</p>
                    <?= Html::a('Go to Airtel Money', ['transaction/airtel-money'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center logout">
                <div class="card-body">
                    <i class="fas fa-sign-out-alt fa-icon"></i>
                    <h5 class="card-title">Logout</h5>
                    <p class="card-text">Sign out of your account.</p>
                    <?= Html::a('Logout', ['site/logout'], ['class' => 'btn btn-danger', 'data-method' => 'post']) ?>
                </div>
            </div>
        </div>
    </div>
</div>