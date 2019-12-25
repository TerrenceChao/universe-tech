<?php

// require_once 'v1/lottery/Lottery.php';
// require_once 'v1/lottery/LotteryHandler.php';
// require_once 'v1/vendor/APIVendor.php';
// require_once 'v1/GameService.php';

// echo '--舊版本--' . PHP_EOL;

// $service = new GameService();
// $target = $service->getTarget(new Lottery(1, '20190903001'));
// $target->getWinningNumber();



require_once 'v2/lottery/Lottery.php';
require_once 'v2/lottery/LotteryHandler.php';
require_once 'v2/vendor/APIVendor.php';
require_once 'v2/GameService.php';

echo PHP_EOL . '--新版本--' . PHP_EOL;

$service = GameService::instance();
$service->getWinningNumber(new Lottery(['game_id' => 2, 'issue' => '20190903001']));
