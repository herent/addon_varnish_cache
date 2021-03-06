<? defined('C5_EXECUTE') or die(_("Access Denied."));

class VarnishStatistics {

	static $statistics;
	protected $statisticItems = array();

	public function getByKey($key) {
		return $this->statisticItems[$key];
	}

	public static function get() {
		if (!isset(self::$statistics)) {
			$vs = new VarnishStatistics();
			try {
				$vs->load();
				self::$statistics = $vs;
			} catch(Exception $e) {
				self::$statistics = false;
			}

		}
		return self::$statistics;
	}

	public function load() {
		$p = Package::getByHandle('varnish_cache');
		$url = $p->config('VARNISH_VARNISHSTATS_PROXY_URL');
		if ($url) {
			$contents = Loader::helper('file')->getContents($url);
			if ($contents) {
				$sx = simplexml_load_string($contents);
				if (isset($sx->stat)) {
					foreach($sx->stat as $stat) {
						$this->statisticItems[(string) $stat->name] = new VarnishStatisticRecord((string) $stat->name, (string) $stat->value, (string) $stat->flag, (string) $stat->description); 
					}
				}
			}
		}

		if (count($this->statisticItems) == 0) {
			throw new Exception('Unable to load statistics.');
		}
	}
	
}