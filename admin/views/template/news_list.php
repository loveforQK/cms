<!DOCTYPE html>
<html>
<head>
    <?= $this->render('_top',['title'=>\app\models\SysNews::$typelist[$type]]) ?>
</head>
<body>
<?= $this->render('_menu') ?>
<ul class="news-ul">
    <?php foreach($list as $val){ ?>
        <li>
            <a href="<?= $detail_uri ?><?= $val['id'] ?>.html" target="_blank">
                <div class="news-left">
                    <p><?= date('m-d',strtotime($val['pubtime'])) ?></p>
                    <span><?= date('Y',strtotime($val['pubtime'])) ?></span>
                </div>
                <div class="news-right">
                    <h3><?= $val['title'] ?></h3>
                    <p><?= $val['info'] ?></p>
                </div>
            </a>
        </li>
    <?php } ?>
</ul>
<?php if($pages > 1){ ?>
    <div class="pagebox">
        <?php if($current > 1){ ?><a href="<?= $page_uri ?><?= ($current-1 == 1)?'index.html':($current-1).'.html' ?>" class="pagelf"><</a><?php } ?>
        <?php for($i=1;$i<=$pages;$i++){ ?>
            <a href="<?= $page_uri ?><?= $i ?>.html" class="page <?php if($i == $current){ ?>  cur<?php } ?>"><?= $i ?></a>
        <?php } ?>
        <?php if($current < $pages){ ?><a href="<?= $page_uri ?><?=($current+1).'.html' ?>" class="pagerg">></a><?php } ?>
    </div>
<?php } ?>
<?= $this->render('_footer') ?>
</body>
</html>