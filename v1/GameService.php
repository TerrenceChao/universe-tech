<?php

/**
 * class GameService: 需實現的 class.
 */
class GameService {
  /** array  號源列表 */
  private $vendorList;

  /** GameService */
  private static $instance;

  /**
   * constructor
   */
  public function __construct()
  {
    $this->vendorList = [];

    // 載入所有的第三方 API 廠商 (號源)
    foreach ($this->getAPIVendorList() as $vendorId => $vendorInfo) {
      $this->vendorList[$vendorId] = new APIVendor($vendorInfo);
    }

    echo 'GameService Constructed' . PHP_EOL;
  }

  /**
   * '模擬'從資料庫中讀取號源列表
   */
  private function getAPIVendorListFromDB(): array
  {
    return [
      [
        'vendor_id' => 18,
        'name' => 'API vendor A (3rd party)',
        'url' => 'http://one.faker/v1'
      ],
      [
        'vendor_id' => 22,
        'name' => 'API vendor B (3rd party)',
        'url' => 'https://two.fake/newly.do'
      ],
      [
        'vendor_id' => 66,
        'name' => 'API vendor C (3rd party)',
        'url' => 'https://three.fake/just.bet'
      ]
    ];
  }

  /**
   * 取得並轉換號源列表
   */
  private function getAPIVendorList(): array
  {
    // 1. read vendor list from DB
    $dbRows = $this->getAPIVendorListFromDB();

    // 2. use collect([...])->keyBy('vendor_id')
    $vendorList = [];
    foreach ($dbRows as $row) {
      $vendorList[$row['vendor_id']] = $row;
    }

    return $vendorList;
  }

  /**
   * '模擬'從資料庫中讀取 '彩種與號源之間的對映列表'
   * @param int $gameId 彩種編號
   */
  private function getLotteryVendorMappingFromDB(int $gameId): array 
  {
    // database records
    $dbRows = [
      // 重慶時時彩
      [
        'game_id' => 1,
        'game_name' => 'chongqing_anytime',
        'vendor_id' => 18,
        'major' => true,
      ],
      // 重慶時時彩
      [
        'game_id' => 1,
        'game_name' => 'chongqing_anytime',
        'vendor_id' => 22,
        'major' => false,
      ],
      // 北京11選5
      [
        'game_id' => 2,
        'game_name' => 'beijing_11x5',
        'vendor_id' => 66,
        'major' => true,
      ],
      // 北京11選5
      [
        'game_id' => 2,
        'game_name' => 'beijing_11x5',
        'vendor_id' => 18,
        'major' => false,
      ],
    ];

    // 2. filter target gameId
    $list = [];
    foreach ($dbRows as $row) {
      if ($gameId !== $row['game_id']) {
        continue;
      }

      $list[] = $row;
    }

    return $list;
  }

  /**
   * get the collection by the given key: vendor_id
   * @param array $lotteryVendorMapping 彩種與號源之間的對映列表
   */
  private function keyByVendorId(array $lotteryVendorMapping): array
  {
    $list = [];
    foreach ($lotteryVendorMapping as $vendorInfo) {
      $vendorId = $vendorInfo['vendor_id'];
      $list[$vendorId] = [
        'game_id' => $vendorInfo['game_id'],
        'game_name' => $vendorInfo['game_name'],
        'major' => $vendorInfo['major'],
        'vendor' => $this->vendorList[$vendorId]
      ];
    }

    return $list;
  }

  /**
   * 用來取得特定的 LotteryHandler
   * @param Lottery $lottery 指定彩種
   */
  public function getTarget(Lottery $lottery): LotteryHandler
  {
    $gameId = $lottery->gameId;
    $lotteryVendorMapping = $this->getLotteryVendorMappingFromDB($gameId);
    $vendorList = $this->keyByVendorId($lotteryVendorMapping);

    return new LotteryHandler($lottery, $vendorList);
  }
}