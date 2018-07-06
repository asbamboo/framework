<?php
use asbamboo\framework\_test\fixtures\controller\Home;
use asbamboo\framework\_test\fixtures\controller\MultiWord;

return  [
    ['id' => 'home', 'path' => '/' , 'callback' => Home::class . ':index'],
    ['id' => 'multi_word', 'path' => '/multi_word' , 'callback' => MultiWord::class . ':mutilIndex'],
];