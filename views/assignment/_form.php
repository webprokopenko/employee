<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Assignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assignment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Order::find()->asArray()->all(),'id','id')) ?>

    <?= $form->field($model, 'employee_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Employee::find()->all(),'id','fullName')) ?>

    <?= $form->field($model, 'position_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Position::find()->all(),'id','Name')) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary')->textInput() ?>

    <?= $form->field($model, 'active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
