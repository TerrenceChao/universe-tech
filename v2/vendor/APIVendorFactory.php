<?php

require_once 'APIVendor.php';
require_once 'FirstAPIVendor.php';
require_once 'SecondAPIVendor.php';
require_once 'ThirdAPIVendor.php';

/**
 * class APIVendorFactory: 第三方 API 廠商產生器 (工廠模式)
 */
class APIVendorFactory {
  private static $instance;

  /**
   * constructor
   */ 
  private function __construct()
  {}

  /**
   * 取得 APIVendorFactory 實例
   */
  public static function instance()
  {
    if (empty(self::$instance)) {
      self::$instance = new APIVendorFactory();
    }

    return self::$instance;
  }

  /**
   * 建立號源實體
   * @param array $vendorInfo
   */
  public function create(array $vendorInfo): APIVendor
  {
    $vendorId = $vendorInfo['vendor_id'];
    switch ($vendorId) {
      case 18:
        return new FirstAPIVendor($vendorInfo);
      case 22:
        return new SecondAPIVendor($vendorInfo);
      case 66:
        return new ThirdAPIVendor($vendorInfo);
      default:
        throw new Exception('非法的第三方 API 廠商 (號源)');
    }
  }
}