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

$ameba = new Auctions('appid','secret key');

$categorys = $yahooa->request('categoryTree');
$leaf = $yahooa->request('categoryLeaf',array('category' => '2084055844'));
$selling = $yahooa->request('sellingList',array('sellerID' => '****'));
$search = $yahooa->request('search',array('query' => '****'));
$item = $yahooa->request('auctionItem',array('auctionID' => '****'));

```