<?php
/**
 * Copyright (c) 2019 PHP-QuickORM ImageResize
 */

require_once '../ImageResize.php';

$a = new ImageResize(realpath('../Windows.jpg'),800,600);
$a->addTextMark("zzfly.net", 5, "#cccccc", "right", "bottom",10);
$a->addImageMark(realpath('../Windows.jpg'),100,80,"right","bottom",50);
$a->render();