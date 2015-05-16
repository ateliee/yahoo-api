# yahoo検索API
yahoo API(http://developer.yahoo.co.jp/)のPHPクラス版。   
とりあえずオークションのみ作ってみました。

## 使い方
```
    "require": {
        "ateliee/yahooAPI": "dev-master"
    }
```

```
use yahooAPI\Auctions;
...

$auctions = new Auctions('appid','secret key');

$categorys = $auctions->request('categoryTree');
$leaf = $auctions->request('categoryLeaf',array('category' => '2084055844'));
$selling = $auctions->request('sellingList',array('sellerID' => '****'));
$search = $auctions->request('search',array('query' => '****'));
$item = $auctions->request('auctionItem',array('auctionID' => '****'));

```