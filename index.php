<?php
require __DIR__.'/App/Lib/SplClassLoader.php';
require __DIR__.'/App/App.php';

error_log('----------------New Call----------------');

$LibLoader = new SplClassLoader('Lib', __DIR__.'/App');
$LibLoader->register();
$StrategyLoader = new SplClassLoader('Strategy', __DIR__.'/App');
$StrategyLoader->register();
$UtilsLoader = new SplClassLoader('Utils', __DIR__.'/App');
$UtilsLoader->register();
$ModelLoader = new SplClassLoader('Model', __DIR__.'/App');
$ModelLoader->register();
$ControllersLoader = new SplClassLoader('Controllers', __DIR__.'/App');
$ControllersLoader->register();

$app = new App;
$app->run();
?>
