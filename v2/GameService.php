<?php

require_once 'vendor/APIVendorFactory.php';

/**
 * class GameService: 需實現的 class.
 */
class GameService {
  /** array  號源列表 */
  private $vendorList;

  /** array  LotteryHandler列表 */
  private $mapping;

  /** GameService */
  private static $instance;

  /**
   * constructor
   */
  private function __construct()
  {
    $this->vendorList = [];
    $this->mapping = [];

    $factory = APIVendorFactory::instance();
    // 載入所有的第三方 API 廠商 (號源)
    foreach ($this->getAPIVendorList() as $vendorId => $vendorInfo) {
      $this->vendorList[$vendorId] = $factory->create($vendorInfo);
    }

    echo 'GameService Constructed' . PHP_EOL;
  }

  /**
   * 取得 GameService 實例
   */
  public static function instance(): GameService
  {
    if (empty(self::$instance)) {
      self::$instance = new GameService();
    }

    return self::$instance;
  }

  /**
   * '模擬'從資料庫中讀取號源列表
   */
  private function getAPIVendorListFromDB(): array
  {
    return [
      [
        'vendor_id' => 18,
        'name' => 'FirstAPIVendor',
        'url' => 'http://one.faker/v1'
      ],
      [
        'vendor_id' => 22,
        'name' => 'SecondAPIVendor',
        'url' => 'https://two.fake/newly.do'
      ],
      [
        'vendor_id' => 66,
        'name' => 'ThirdAPIVendor',
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
        'vendor_id' => 22,
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
   * 用來取得特定彩種的開獎號碼
   * @param Lottery $lottery 指定彩種
   */
  public function getWinningNumber(Lottery $lottery): string
  {
    $gameId = $lottery->getGameId();
    if (empty($this->mapping[$gameId])) {
      $lotteryVendorMapping = $this->getLotteryVendorMappingFromDB($gameId);
      $vendorList = $this->keyByVendorId($lotteryVendorMapping);
      $this->mapping[$gameId] = new LotteryHandler($vendorList);
    }

    return $this->mapping[$gameId]->getWinningNumber($lottery);
  }
}