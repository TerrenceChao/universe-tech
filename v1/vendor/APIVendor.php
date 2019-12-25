<?php

require_once dirname(dirname(__FILE__)) . '/lottery/Lottery.php';

/**
 * class APIVendor: 第三方 API 廠商 (號源)
 */
class APIVendor {
  /** int 號源ID */
  protected $vendorId;
  /** string 號源名稱 */
  protected $name;
  /** string 號源url */
  protected $url;

  /**
   * constructor
   * @param array $data 一筆在資料庫中的號源紀錄
   */
  public function __construct(array $data)
  {
    $this->vendorId = $data['vendor_id'];
    $this->name = $data['name'];
    $this->url = $data['url'];
  }

  /**
   * 用來取得特定彩種的開獎號碼
   * @param Lottery $lottery 指定彩種
   */
  public function getWinningNumber(Lottery $lottery): string
  {
    return '0,6,2,2,3';
  }
}
