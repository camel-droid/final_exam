<?php
// 外部ファイルの読み込み
require_once dirname(__DIR__)."/dao/ProductDAO.php";
?>
<?php
class ProductService {
	
	/**
	 * プロパティ
	 */
	private $parameters = [];
	private $action;

	/**
	 * コンストラクタ
	 * @param array リクエストパラメータ
	 */
	function __construct(array $request) {
		isset($request["action"]) ? $this->action = $request["action"] : $this->action = null;
		$params = [];
		$params["action"] = $this->action;
		switch ($this->action) {
			case "list":
			break;
			default:
			break;
		}
	}

	function execute():string {
		session_start();
		$dao = new ProductDAO();
		switch ($this->action) {
			case "list":
				$products = $dao->findAll();
				$_SESSION["products"] = $products;
				return "list.php";
			break;
		}
		return "top.php";
	}
}