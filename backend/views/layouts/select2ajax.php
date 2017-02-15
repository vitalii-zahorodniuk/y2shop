<?php
use backend\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$this->assetBundles = [];

$this->beginPage();
$this->beginBody();

echo $content;

$this->endBody();
$this->endPage();
