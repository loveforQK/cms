<!DOCTYPE html>
<html>
<head>
    <?= $this->render('_top',['title'=>$model->title]) ?>
</head>
<body>
<?= $this->render('_menu') ?>
<?= $model->content ?>
<?= $this->render('_footer') ?>
</body>
</html>