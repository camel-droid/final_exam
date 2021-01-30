<?php
// 外部ファイルの読み込み
require_once dirname(__DIR__)."/dao/ProductDAO.php";
?>
<?php
/**
 * 商品に関するサービスを担当するクラス
 * TODO: 【要検討】以下の項目の検討の余地あり。
 * 					1. 入力チェックとエラーの場合の例外処理の仕様
 * 					2. ViewとModelのインタフェースとなるビジネス用DTOの追加
 */
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
		// リクエストパラメータの取得
		isset($request["action"]) ? $this->action = $request["action"] : $this->action = null;
		isset($request["mode"]) ? $this->mode = $request["mode"] : $this->mode = null;

		isset($request["id"]) ? $this->parameters["id"] = $request["id"] : $this->parameter["id"] = 0;
		isset($request["category"]) ? $this->parameters["category"] = $request["category"] : $this->parameter["category"] = "";
		isset($request["name"]) ? $this->parameters["name"] = $request["name"] : $this->parameter["name"] = "";
		isset($request["price"]) ? $this->parameters["price"] = $request["price"] : $this->parameter["price"] = 0;
		isset($request["detail"]) ? $this->parameters["detail"] = $request["detail"] : $this->parameter["detail"] = "";
	}

	/**
	 * 処理を実行する。
	 * @return string 遷移先ファイル名
	 */
	function execute():string {
		session_start();
		$dao = new ProductDAO();
		// 処理の分岐
		switch ($this->action) {
			/* 一覧表示 */
			case "list":
				$_SESSION["products"] = $dao->findAll();
				$page = "{$this->action}.php";
				break;
			/* 登録処理・更新処理・削除処理 */
			case "entry":
			case "update":
			case "delete":
				$page = $this->switchExecute($dao, $this->action, $this->mode);
				break;
			default:
				$page = "top.php";
				break;
		}
		return $page;
	}

	/**
	 * パラメータからProductDTOのインスタンスを生成する。
	 * @return ProductDTO
	 */
	private function getInstanceOfProduct():ProductDTO {
		isset($this->parameters["id"]) ? $id = $this->parameters["id"] : $id = 0;
		isset($this->parameters["category"]) ? $category = $this->parameters["category"] : $category = "";
		isset($this->parameters["name"]) ? $name = $this->parameters["name"] : $name = "";
		isset($this->parameters["price"]) ? $price = $this->parameters["price"] : $price = 0;
		isset($this->parameters["detail"]) ? $detail = $this->parameters["detail"] : $detail = "";
		$this->parameters = [];
		return new ProductDTO($id, $category, $name, $price, $detail);
	}

	/**
	 * セッションに格納するパラメータを生成する。
	 * @param string
	 * @param string
	 * @param ProdcutDTO
	 * @return array
	 */
	private function setParameters(string $action, ?string $mode, ProductDTO $product = null):array {
		$parameters = [];
		$parameters["action"] = $this->action;
		$parameters["mode"] = $mode;
		if (!is_null($product)) $parameters["product"] = $product;
		return $parameters;
	}

	/**
	 * セッションを破棄する。
	 */
	private function destroySession():void {
		unset($_SESSION["parameters"]);
		unset($_SESSION["products"]);
		unset($_SESSION);
	}

	/**
	 * CRUD処理を実行する。
	 * @param ProductDAO
	 * @param array 処理対象のProductDTOと処理名を格納した連想配列
	 * @return string 遷移先ファイル名
	 */
	private function doExec(ProductDAO $dao, array $parameters):string {
		$product = $parameters["product"];
		$page = "{$parameters["mode"]}.php";
		switch ($this->action) {
			case "entry":
				$dao->insert($product);
				break;
			case "update":
				$dao->update($product);
				break;
			case "delete":
				$dao->delete($product->getId());
				break; 
		}
		return $page;
	}

	/**
	 * 処理を切り替えて実行する。
	 * @param ProductDAO
	 * @param string actionキー
	 * @param string modeキー：省略された場合はnull
	 * TODO: 【要検討】ProductDAOはProductService::daoプロパティが渡されているだめなので、引数にせず直接ProductService::daoプロパティを参照するほうがいいかもしれない。
	 */
	private function switchExecute(ProductDAO $dao, string $action, string $mode = null):string {
		if (is_null($mode)) {
			/* 処理選択後の初期画面表示：処理初期画面への遷移 */
			// 商品クラスを取得
			$page = "{$action}.php";
			$mode = "confirm";
			$product = null;
			if ($action !== "entry") {
				$product = $dao->findById($this->parameters["id"]);
				if ($action === "delete") {
					// 削除処理では初期画面が確認画面なので完了画面へ遷移
					$page = "{$mode}.php";
					$mode = "complete";
				}
			}
			$parameters = $this->setParameters($action, $mode, $product);
			$_SESSION["parameters"] = $parameters;
		} elseif ($mode === "confirm") {
			/* 処理の確認画面表示：確認画面への遷移 */
			$page = "{$mode}.php";
			$mode = "complete";
			$product = $this->getInstanceOfProduct();
			$parameters = $this->setParameters($action, $mode, $product);
			$_SESSION["parameters"] = $parameters;
		} elseif ($mode === "complete") {
			/* 処理完了画面表示：完了画面への遷移 */
			$page = $this->doExec($dao, $_SESSION["parameters"]);
			$this->destroySession();
		}
		return $page;
	}
}