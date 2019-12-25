<?php

require_once 'APIVendor.php';

class ThirdAPIVendor extends APIVendor {
  /** array 針對不同彩種定義所需的參數 */
  private $lotteries;

  /**
   * constructor
   * @param array $data 一筆在資料庫中的號源紀錄
   */
  public function __construct(array $data)
  {
    parent::__construct($data);
    // TODO: 定義不同彩種所需的參數
    // $this->lotteries = [];
  }

  /**
   * 用來取得特定彩種的開獎號碼
   * @param Lottery $lottery 指定彩種
   */
  public function getWinningNumber(Lottery $lottery): string
  {
    $gameId = $lottery->getGameId();
    // TODO: do request with specific 'gameId' and/or 'issue' ...

    return '0,6,2,2,3';
  }
}
