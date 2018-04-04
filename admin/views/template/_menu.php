<?php
use app\models\SysPage;

$list1 = SysPage::getMenu1List();
$list2 = SysPage::getMenu2List();
?>
<ul>
    <li><a href="/">首页</a></li>
    <li><a href="/business">动态</a></li>
    <?php foreach($list1 as $v){ ?>
        <li><a href="/page/<?= $v->dirname ?>/"><?= $v->title ?></a></li>
    <?php } ?>
</ul>
<ul>
    <?php foreach($list2 as $v){ ?>
        <li><a href="/page/<?= $v->dirname ?>/"><?= $v->title ?></a></li>
    <?php } ?>
</ul>