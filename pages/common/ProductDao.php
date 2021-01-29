<?php
// 外部ファイルの読み込み
require_once(__DIR__."/ProductDto.php");
?>
<?php
class ProductDao {
  /**
   * クラス定数
   */
  /* データベース接続情報 */
  const DB_DSN = "mysql:host=localhost;dbname=productdb;charset=utf8";
  const DB_USER = "productdb_admin";
  const DB_PASSWORD = "admin123";
  /* SQL文字列 */
  const SQL_FIND = "select * from product";
  const SQL_FIND_BY_ID = self::SQL_FIND." where id = ?";
  const SQL_INSERT = "insert into product (category, name, price, detail) values (:category, :name, :price, :detail)";
  const SQL_UPDATE = "update product set category = :category, name = :name, price = :price, detail = :detail where id = :id";
  const SQL_DELETE_BY_ID = "delete from product where id = ?";

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
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare(self::SQL_FIND);
      // SQLの実行と結果セットの取得
      $pstmt->execute();
      $records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
      // 結果セットから商品クラスの配列に入れ替え
      $products = $this->convertToDtos($records);
      return $products;
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }

  /**
   * 結果セットをDTOクラスのインスタンスの配列に変換する。
   * @param array 結果セット
   * @return array 変換されたDTOクラスのインスタンスの配列
   */
  private function convertToDtos(array $records):array {
    foreach ($records as $record) {
      $product = $this->convertToDto($record);
      $products[] = $product;
    }
    return $products;
  }
  
  /**
   * 結果セットの1レコードをDTOクラスのインスタンスに変換する。
   * @param 結果セットの1レコード：フィールド名をキーとする連想配列
   * @return ProductDto 変換されたDTOクラスのインスタンス
   */
  private function convertToDto(array $record):ProductDto {
    $id = $record["id"];
    $category = $record["category"];
    $name = $record["name"];
    $price = $record["price"];
    $detail = $record["detail"];
    $product = new ProductDto($id, $category, $name, $price, $detail);
    return $product;
  }

  /**
   * 商品ID検索を実行する。
   * @param int 商品ID
   * @return Product|null 商品IDに合致する商品がある場合はそのProductクラスのインスタンス、それ以外の場合はnull
   */
  function findById(int $id):ProductDto {
    // SQL実行オブジェクトと結果セットの初期化
    $pstmt = null;
    $records = [];
    /* 商品ID検索処理の実行 */
    try {
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare(self::SQL_FIND_BY_ID);
      // プレースホルダにパラメータを設定
      $pstmt->bindValue(1, $id);
      // SQLの実行と結果セットの取得
      $pstmt->execute();
      $records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
      // 結果セットから商品クラスをインスタンス化
      $product = null;
      if (count($records) > 0) {
        $product = $this->convertToDto($records[0]);
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
      // プレースホルダに設定するパラメータの連想配列を設定
      $params = $this->createPrametersBy($product);
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare(self::SQL_INSERT);
      // SQLの実行
      $pstmt->execute($params);
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }

  private function createPrametersBy(ProductDto $product, ?string $foruse = null):array {
    $params = [];
    if (!is_null($foruse)) $params[":id"] = $product->getId(); 
    $params[":category"] = $product->getCategory();
    $params[":name"] = $product->getName();
    $params[":price"] = $product->getPrice();
    $params[":detail"] = $product->getDetail();
    return $params;
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
      // プレースホルダに設定するパラメータの連想配列を設定
      $params = $this->createPrametersBy($product, "for_upate");
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
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare(self::SQL_DELETE);
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