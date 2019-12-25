<?php

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
    try {
      include_once $vendorInfo['name'] . '.php';
      return new $vendorInfo['name']($vendorInfo);
    } catch (Exception $e) {
      throw new Exception('非法的第三方 API 廠商 (號源)');
    }
  }
}