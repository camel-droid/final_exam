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
	private $mode;

	/**
	 * コンストラクタ
	 * @param array リクエストパラメータ
	 */
	function __construct(array $request) {
		isset($request["action"]) ? $this->action = $request["action"] : $this->action = null;
		isset($request["mode"]) ? $this->mode = $request["mode"] : $this->mode = null;

		isset($request["id"]) ? $this->parameters["id"] = $request["id"] : $this->parameter["id"] = 0;
		isset($request["category"]) ? $this->parameters["category"] = $request["category"] : $this->parameter["category"] = "";
		isset($request["name"]) ? $this->parameters["name"] = $request["name"] : $this->parameter["name"] = "";
		isset($request["price"]) ? $this->parameters["price"] = $request["price"] : $this->parameter["price"] = 0;
		isset($request["detail"]) ? $this->parameters["detail"] = $request["detail"] : $this->parameter["detail"] = "";

		switch ($this->action) {
			case "list":
			break;
			default:
			break;
		}
	}

	/**
	 * 処理を実行する。
	 * @return string 遷移先ファイル名
	 */
	function execute():string {
		session_start();
		$dao = new ProductDAO();
		switch ($this->action) {
			case "list":
				$products = $dao->findAll();
				$_SESSION["products"] = $products;
				$page = "list.php";
				break;
			case "entry":
				if (is_null($this->mode)) {
					$page = "entry.php";
				} elseif ($this->mode === "confirm") {
					$parameters["action"] = $this->action;
					$parameters["product"] = $this->getInstanceProduct();
					$_SESSION["parameters"] = $parameters;
					$page = "confirm.php";
				} elseif ($this->mode === "complete") {
					$parameters = $_SESSION["parameters"];
					$dao->insert($parameters["product"]);
					$page = "complete.php";
					unset($_SESSION["action"]);
					unset($_SESSION["product"]);
				}
				return $page;
				break;
			case "update":
				if (is_null($this->mode)) {
					$product = $dao->findById($this->parameters["id"]);
					$this->parameters["action"] = $this->action;
					$this->parameters["product"] = $product;
					$_SESSION["parameters"] = $this->parameters;
					$page = "update.php";
				} elseif ($this->mode === "confirm") {
					$parameters["action"] = $this->action;
					$parameters["product"] = $this->getInstanceProduct();
					$_SESSION["parameters"] = $parameters;
					$page = "confirm.php";
				} elseif ($this->mode === "complete") {
					$parameters = $_SESSION["parameters"];
					$dao->update($parameters["product"]);
					$page = "complete.php";
					unset($_SESSION["action"]);
					unset($_SESSION["product"]);
				}
				return $page;
				break;
			case "delete":
				if (is_null($this->mode)) {
					$product = $dao->findById($this->parameters["id"]);
					$this->parameters["action"] = $this->action;
					$this->parameters["product"] = $product;
					$_SESSION["parameters"] = $this->parameters;
					$page = "confirm.php";
				} elseif ($this->mode === "complete") {
					$parameters = $_SESSION["parameters"];
					$dao->delete($parameters["product"]->getId());
					$page = "complete.php";
					unset($_SESSION["action"]);
					unset($_SESSION["product"]);
				}
				return $page;
				break;
			default:
				$page = "top.php";
				break;
		}
		return $page;
	}

	private function getInstanceProduct():ProductDTO {
		isset($this->parameters["id"]) ? $id = $this->parameters["id"] : $id = 0;
		isset($this->parameters["category"]) ? $category = $this->parameters["category"] : $category = "";
		isset($this->parameters["name"]) ? $name = $this->parameters["name"] : $name = "";
		isset($this->parameters["price"]) ? $price = $this->parameters["price"] : $price = 0;
		isset($this->parameters["detail"]) ? $detail = $this->parameters["detail"] : $detail = "";
		$this->parameters = [];
		return new ProductDTO($id, $category, $name, $price, $detail);
	}

}