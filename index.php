<?php
require_once 'ImageResize.php';

$a = new ImageResize('Windows.jpg',800,600);
$a->addTextMark("zzfly.net", 5, "#cccccc", "right", "bottom",10);
$a->addImageMark('Windows.jpg',100,80,"right","bottom",50);
$a->render();