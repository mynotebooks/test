<!-- 医療用医薬品の添付文書情報を管理する -->
<?php
class PmdaRx extends AppModel {
	const URL_PACKINS_SEARCH = 'http://www.info.pmda.go.jp/psearch/PackinsSearch?'; 

	public $useTable = 'db_pmda_rx';
    public $primaryKey = 'id';

    public $hasOne = 'PmdaRxRisk';

    # 関連ファイルを取得するddd
	public function download($id = null) {
		ini_set('user_agent', 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0');
		$html = @file_get_contents("http://www.info.pmda.go.jp/go/pack/$id?view=foot");
		if(!$html) {
			return false;
		}
		
		$dir = "files/pmda/rx/$id";
		if(!file_exists($dir)) {
			mkdir($dir, 0777);
			chmod($dir, 0777);
		}

		if(preg_match('/downfiles\/ph\/SGM\/(.+.zip)/', $html, $match)) {
			if(!file_exists("$dir/$match[1]")) {
				$data = file_get_contents("http://www.info.pmda.go.jp/$match[0]", FILE_BINARY);
				file_put_contents("$dir/$match[1]", $data);
			}
		}
		if(preg_match('/downfiles\/ph\/PDF\/(.+.pdf)/', $html, $match)) {
			if(!file_exists("$dir/$match[1]")) {
				$data = file_get_contents("http://www.info.pmda.go.jp/$match[0]", FILE_BINARY);
				file_put_contents("$dir/$match[1]", $data);
			}
		}
		if(preg_match('/downfiles\/ph\/GUI\/(.+.pdf)/', $html, $match)) {
			if(!file_exists("$dir/$match[1]")) {
				$data = file_get_contents("http://www.info.pmda.go.jp/$match[0]", FILE_BINARY);
				file_put_contents("$dir/$match[1]", $data);
			}
		}
		if(preg_match('/go\/interview\/.+?([^\"\/]+)/', $html, $match)) {
			if(!file_exists("$dir/$match[1].pdf")) {
				$data = file_get_contents("http://www.info.pmda.go.jp/$match[0]", FILE_BINARY);
				file_put_contents("$dir/$match[1].pdf", $data);
			}
		}
	}

    # PackinsSearchでキーワード検索する		
    public function findIDsByKeyword($keyword, $effect = '') {
    	return $this->findIDs(array('effect' => $effect, 'keyword1'=> $keyword));
    }

    # PackinsSearchで製品名検索する	
    public function findIDsByName($name, $effect = '') {
    	return $this->findIDs(array('effect' => $effect, 'item1' => 'brandname', 'keyword1' => $name));
    }

    # PackinsSearchで検索する
    protected function findIDs($keywords) {
    	# デフォルト値
    	$query = array(
    		'dragname' => '',
    		'effect' => '',
    		'item1' => 'allsearch',
    		'keyword1' => '',
    		'type1' => 'and',
    		'item2' => 'allsearch',
    		'keyword2' => '',
    		'type2' => 'and',
    		'item3' => 'allsearch',
    		'keyword3' => '',
    		'type3' => 'and',
    		'count' => '1000',
    		'start' => '1'
    	);

    	# キーワードの指定
    	foreach ($keywords as $key => $value) {
    		if(array_key_exists($key, $query)) {
    			$query[$key] = urlencode(mb_convert_encoding($value, 'EUC-JP'));
    		}
        }

        # URLの組み立て
        $sub = array();
    	foreach ($query as $key => $value) {
    		$sub[] = "{$key}={$value}";
        }
		$url = self::URL_PACKINS_SEARCH . implode('&', $sub);
		
		# ファイルの読み込み
		ini_set('user_agent', 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0');
    	$html = @file_get_contents($url);
    	if(!$html) {
    		return false;
    	}
    	$html = mb_convert_encoding($html, 'UTF-8', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');
    	$html = mb_convert_kana($html, "as");
    	$html = Util::zen2han($html);
    	$html = preg_replace('/(?:\n|\r|\r\n)/', '', $html);
    	preg_match_all("/\/([0-9]{7}[A-Z][0-9]{4}_[0-9]_[0-9]{2})\/.+?>(.+?)<\/a>/ism", $html, $ids, PREG_PATTERN_ORDER);
    	
    	if(!empty($ids)) {
    		return array_combine($ids[1], $ids[2]);
    	}

    	return false;
    }
}
