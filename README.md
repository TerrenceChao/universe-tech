#### 1. 請實作下列需求
-------------
詳見 v1, v2 內容以及 main.php 的使用方式。
<br/>

#### 2. 上面程式或規格可能存在什麼潛在問題？還可以怎樣優化？
-------------
A. 每次 new xxxxClass 都需要重新再獲取資源。其實可以改以 singleton pattern 取代。(詳見'v2/GameService.php')

B. 關於取得號源 API 的規格在 '回傳列表' 的情況下 (雖然是第三方 API 廠商，但這邊僅針對規格做討論)：
當欲取得同彩種的列表時可能有大量的列表，這時一般來說可以用 SQL 的 limit, skip 來取得一部份的列表；但是用到 skip 表示 database 會從頭搜尋第一筆紀錄接著再擷取需要 shift 的筆數，透過分頁逐次取得部份列表時效能會越來越慢。
因此建議用 limit 和 opentime 取得列表。以 opentime 作為 where 條件式過濾，再以 limit 限制筆數。
<br/>

#### 3. 如果要加入第三家號源，會怎麼進行擴充？
-------------
定義第三家號源的 class: ThirdAPIVendor.php, 並且在 APIVendorFactory.php 的 function: create 定義對應的 vendor ID.
詳見 'v2/vendor/ThirdAPIVendor.php'
<br/>

#### 4. 每個號源有不同的速率限制，會如何實現限流，防止被 ban？
-------------
##### 假設號源一限制 5 秒一次，號源二限制 3 秒一次．同號源不同彩種是分開計算的．

A. 針對[特定號源+特定彩種]設定 throttle. 不一定要用 Laravel 本身的 middleware: throttle, 可尋找套件，該套件可以針對同樣的 route 不同的 querystring 設定 rate limit. 若回傳錯誤的原因是 rate limit 達到上限，則循序透過下一個副號源取得結果 (或將此次 job 視為失敗)。

B. 利用 cache (redis) 紀錄[特定號源+特定彩種]上一次 call API 的時間，若距離上次呼叫時間太近則不 call API. 循序透過下一個副號源取得結果 (或將此次 job 視為失敗)。
<br/>

#### 5. 開獎時間並非準時，您會如何實現重試機制？
-------------
##### 號源站並非能第一時間抓到該彩種中獎號碼，因此存在抓不到的可能性
根據 class UpdateWinningNumberJob 的意圖猜測屬於 queue 的機制；並且因每個號源有不同的速率限制，在同一次 job 中再次重試很大可能達到 rate limit 上限或是影響到其他不同 request 的需求。這裡我會選擇將此次 job 直接視為失敗並重新放入 queue 中排隊執行。
<br/>

#### 6. 可以實現哪些手段來減少程式運行時間？
-------------
A. 針對 hot data (比如一天內的各彩種開獎) 多利用 cache. 減少存取 DB 或 call API 的次數。

B. 使用 singleton pattern, 不重複建立。

C. v1/GameService.php 中的 getTarget(...) 每次都會從 DB 取得資料，重複資料取了多次；改以 v2/GameService.php 中的方式，多一個 mapping 變數，呼叫 getWinningNumber(...) 時會儲存之前取得的資料，以減少運行時間。

以上 B., C. 可比較 v1/GameService.php, v2/GameService.php 的差異。