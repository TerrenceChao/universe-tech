<?php

/**
 * class Lottery: 彩種
 */
class Lottery {
  /** int 彩種編號 */
  public $gameId;
  /** string, 此 lottery 期號（e.g. "20190903001"）*/
  public $issue;

  /**
   * constructor
   * @param int $gameId 彩種編號
   * @param string $issue 此 lottery 期號
   */
  public function __construct(int $gameId, string $issue)
  {
    $this->gameId = $gameId;
    $this->issue = $issue;
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
