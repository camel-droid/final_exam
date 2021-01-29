<?php
// 外部ファイルの読み込み
require_once __DIR__."/service/ProductService.php";
?>
<?php
class Controller {

	const DEFAULT_URL = "/lectures/final_exam/pages/";

	static function execute():string {
		$service = new ProductService($_REQUEST);
		$nextPage = $service->execute();
		$url = self::createUrl($nextPage);
		header("Location: {$url}");
	}

	private static function createUrl(string $fileName):string {
		$appRoot = "http://{$_SERVER['HTTP_HOST']}".self::DEFAULT_URL;
		return "{$appRoot}{$fileName}";
	}

}