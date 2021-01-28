<?php
// 外部ファイルの読み込み
require_once("./ProductDto.php");
?>
<?php
class ProductDao {
  /**
   * クラス定数：データベース接続情報
   */
  const DB_DSN = "mysql:host=localhost;dbname=productdb;charset=utf8";
  const DB_USER = "productsb_admin";
  const DB_PASSWORD = "admin123";
  
  /**
   * プロパティ
   */
  private $pdo;
  
  /**
   * コンストラクタ
   */
  function __construct() {
    try {
      $this->pdo = new PDO(self::DB_DSN, self::DB_USER, self::DB_PASSWORD);  
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($this->pdo);
    }
  }
  
  /**
   * 全件検索を行う。
   * @return array Productクラスのインスタンスの配列
   */
  function findAll():array {
    // SQL実行オブジェクトと結果セットの初期化
    $pstmt = null;
    $records = [];
    /* 全件検索処理の実行 */
    try {
      // 実行するSQLの設定
      $sql = "select * from product";
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare($sql);
      // SQLの実行と結果セットの取得
      $pstmt->execute();
      $records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
      // 結果セットから商品クラスの配列に入れ替え
      $products = [];
      foreach ($records as $record) {
        $id = $record["id"];
        $category = $record["category"];
        $name = $record["id"];
        $price = $record["price"];
        $detail = $record["detail"];
        $product = new ProductDto($id, $category, $name, $price, $detail);
        $products[] = $product;
      }
      return $products;
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }
  
  /**
   * 商品ID検索を実行する。
   * @param int 商品ID
   * @return Product|null 商品IDに合致する商品がある場合はそのProductクラスのインスタンス、それ以外の場合はnull
   */
  function findById(int $id):Product {
    // SQL実行オブジェクトと結果セットの初期化
    $pstmt = null;
    $records = [];
    /* 商品ID検索処理の実行 */
    try {
      // 実行するSQLの設定
      $sql = "select * from product where id = ?";
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare($sql);
      // プレースホルダにパラメータを設定
      $pstmt->bindValue(1, $id);
      // SQLの実行と結果セットの取得
      $pstmt->execute();
      $records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
      // 結果セットから商品クラスをインスタンス化
      $product = null;
      if (count($records) > 0) {
        $id = $records[0]["id"];
        $category = $records[0]["category"];
        $name = $records[0]["name"];
        $price = $records[0]["price"];
        $detail = $records[0]["detail"];
       $product = new ProductDto($id, $category, $name, $price, $detail);
      }
      return $product;
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }
  
  /**
   * Productテーブルにレコードを追加する。
   * @param Product 追加する商品のProductクラスのインスタンス
   */
  function insert(ProductDto $product):void {
    // SQL実行オブジェクトの初期化
    $pstmt = null;
    /* 新規追加処理の実行 */
    try {
      // 実行するSQLの設定
      $sql = "insert into product (category, name, price, detail) values (:category, :name, :price, :detail)";
      // プレースホルダに設定するパラメータの連想配列を設定
      $params = [];
      $params[":category"] = $product->getCategory();
      $params[":name"] = $product->getName();
      $params[":price"] = $product->getPrice();
      $params[":detail"] = $product->getDetail();
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare($sql);
      // SQLの実行
      $pstmt->execute($params);
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }
  
  /**
   * Productテーブルにレコードを更新する。
   * @param Product 更新する商品のProductクラスのインスタンス
   */
  function update(ProductDto $product):void {
    // SQL実行オブジェクトの初期化
    $pstmt = null;
    /* 更新処理の実行 */
    try {
      // 実行するSQLの設定
      $sql = "update product set category = :category, name = :name, price = :price, detail = :detail where id = :id";
      // プレースホルダに設定するパラメータの連想配列を設定
      $params = [];
      $params[":id"] = $product->getId();
      $params[":category"] = $product->getCategory();
      $params[":name"] = $product->getName();
      $params[":price"] = $product->getPrice();
      $params[":detail"] = $product->getDetail();
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare($sql);
      // SQLの実行
      $pstmt->execute($params);
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }
  
  function delete(int $id):void {
    // SQL実行オブジェクトの初期化
    $pstmt - null;
    /* 削除処理の実行 */
    try {
      // 実行するSQLの設定
      $sql = "delete from product where id = ?";
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare($sql);
      // プレースホルダをパラメータを設定
      $pstmt->bindValue(1, $id);
      // SQLの実行
      $pstmt->execute();
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }
}