<?

defined('C5_EXECUTE') or die("Access Denied.");

class VarnishPageCache extends PageCache {

	public function getVarnishAdminSocket() {
		Loader::library('3rdparty/varnish_admin_socket', 'varnish_cache');
		$p = Package::getByHandle('varnish_cache');
		$s = new VarnishAdminSocket($p->config('VARNISH_CONTROL_TERMINAL_HOST'), $p->config('VARNISH_CONTROL_TERMINAL_PORT'), VARNISH_CACHE_VERSION);
		if ($p->config('VARNISH_CONTROL_TERMINAL_KEY')) {
			$s->set_auth($p->config('VARNISH_CONTROL_TERMINAL_KEY'));
		}
		return $s;
	}

	public function getRecord($mixed) {
		$ur = new UnknownPageCacheRecord();
		return $ur;
	}

	/*

	protected function getCacheFile($mixed) {
		$key = $this->getCacheKey($mixed);
		$filename = $key . '.cache';
		if ($key) {
			if (strlen($key) == 1) {
				$dir = DIR_FILES_PAGE_CACHE . '/' . $key;
			} else if (strlen($key) == 2) {
				$dir = DIR_FILES_PAGE_CACHE . '/' . $key[0] . '/' . $key[1];
			} else {
				$dir = DIR_FILES_PAGE_CACHE . '/' . $key[0] . '/' . $key[1] . '/' . $key[2];
			}
			if ($dir && (!is_dir($dir))) {
				mkdir($dir, DIRECTORY_PERMISSIONS_MODE, true);
			}
			$path = $dir . '/' . $filename;
			return $path;
		}
	}
	*/

	public function purgeByRecord(PageCacheRecord $rec) {
		/*
		$file = $this->getCacheFile($rec);
		if ($file && file_exists($file)) {
			unlink($file);
		}
		*/
	}

	public function flush() {
		$vas = $this->getVarnishAdminSocket();
		$vas->connect(1);
		$vas->purge_url('.');
	}

	public function purge(Page $c) {
		$vas = $this->getVarnishAdminSocket();
		$vas->connect(1);
		if (!$c->getCollectionPath()) {
			$path = '/';
		} else {
			$path = $c->getCollectionPath();
		}
		$vas->purge_url($path);
	}

	public function set(Page $c, $content) {
		/*
		if (!is_dir(DIR_FILES_PAGE_CACHE)) {
			mkdir(DIR_FILES_PAGE_CACHE);
			touch(DIR_FILES_PAGE_CACHE . '/index.html');
		}

		$lifetime = $c->getCollectionFullPageCachingLifetimeValue();
		$file = $this->getCacheFile($c);
		if ($file) {
			$response = new PageCacheRecord($c, $content, $lifetime);
			if ($content) {
				file_put_contents($file, serialize($response));
			}
		}
		*/
	}


}