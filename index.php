<?php
require __DIR__.'/App/Lib/SplClassLoader.php';
require __DIR__.'/App/App.php';

error_log('----------------New Call----------------');

$ControllersLoader = new SplClassLoader('Controller', __DIR__.'/App');
$ControllersLoader->register();
$LibLoader = new SplClassLoader('Lib', __DIR__.'/App');
$LibLoader->register();
$ModelLoader = new SplClassLoader('Model', __DIR__.'/App');
$ModelLoader->register();
$UtilsLoader = new SplClassLoader('Utils', __DIR__.'/App');
$UtilsLoader->register();

$app = new App;
$app->run();
?>
