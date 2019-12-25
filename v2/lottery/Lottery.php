<?php

/**
 * class Lottery: 彩種
 */
class Lottery {
  /** array 此 lottery 所需的所有屬性 */
  private $data;

  /**
   * constructor
   * @param array $data 此 lottery 所需的所有屬性
   */
  public function __construct(array $data)
  {
    $this->data = $data;
  }

  /**
   * 取得彩種編號
   */
  public function getGameId(): int
  {
    return $this->data['game_id'];
  }

  /**
   * 取得指定屬性
   * @param string $key
   */
  public function getValue(string $key)
  {
    return $this->data[$key];
  }

  /**
   * 更新所取得的開獎號碼
   * @param array $newRecords
   */
  public function update(array $newRecords): void
  {
    /**
     * TODO:
     * 1. upsert catch with [gameId, issue]
     * 2. upsert database
     */
  }
}
