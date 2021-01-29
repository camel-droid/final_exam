<?php
// 外部ファイルの読み込み
require_once __DIR__."/DAO.php";
require_once dirname(__DIR__)."/dto/ProductDTO.php";
?>
<?php
class ProductDAO extends DAO {
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
  const SQL_DELETE_BY_ID = "delete from product where id = ?";
  /** プレースホルダ名文字列 */
  const PLACEHOLDER_ID = ":id";
  const PLACEHOLDER_CATEGORY = ":category";
  const PLACEHOLDER_NAME = ":name";
  const PLACEHOLDER_PRICE = ":price";
  const PLACEHOLDER_DETAIL = ":detail";
  /* 処理スウィッチ定数 */
  const OPE_INSERT = true;
  const OPE_UPDATE = false;

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
   * @see PoductDao::createParametersBy(ProductDto, bool)
   */
  function insert(ProductDto $product):void {
    // SQL実行オブジェクトの初期化
    $pstmt = null;
    /* 新規追加処理の実行 */
    try {
      // プレースホルダに設定するパラメータの連想配列を設定
      $params = $this->createPrametersBy($product);
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare($this->createSQL());
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
   * @see PoductDao::createParametersBy(ProductDto, bool)
   */
  function update(ProductDto $product):void {
    // SQL実行オブジェクトの初期化
    $pstmt = null;
    /* 更新処理の実行 */
    try {
      // 実行するSQLの設定
      // プレースホルダに設定するパラメータの連想配列を設定
      $params = $this->createPrametersBy($product, self::OPE_UPDATE);
      // SQL実行オブジェクトを取得
      $pstmt = $this->pdo->prepare($this->createSQL(self::OPE_UPDATE));
      // SQLの実行
      $pstmt->execute($params);
    } catch (PDOException $e) {
      die($e->getMessage());
    } finally {
      unset($pstmt);
    }
  }

  /**
   * 指定されたIDの商品を削除する。
   * @param int 削除する商品ID
   */
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
   * プレースホルダに設定するパラメータの連想配列を生成する。
   * @param ProductDto 対象となる商品クラス
   * @param string 新規追加書であるか更新処理を指定するスウィッチ：true（省略された場合を含む）の場合は新規追加処理、falseの場合は更新処理
   */
  private function createPrametersBy(ProductDto $product, bool $switch = self::OPE_INSERT):array {
    $params = [];
    // 更新処理の場合は商品IDが必要
    if ($switch === self::OPE_UPDATE) $params[self::PLACEHOLDER_ID] = $product->getId();
    // 商品ID以外のフィールドは処理に関係なく必要 
    $params[self::PLACEHOLDER_CATEGORY] = $product->getCategory();
    $params[self::PLACEHOLDER_NAME] = $product->getName();
    $params[self::PLACEHOLDER_PRICE] = $product->getPrice();
    $params[self::PLACEHOLDER_DETAIL] = $product->getDetail();
    return $params;
  }

  /**
   * SQL文字列を生成する。
   * @param bool true（省略した場合も含む）の場合はinsert文、それ以外の場合はupdate文
   */
  private function createSQL(bool $for_insert = self::OPE_INSERT):string {
    $sql = "";
    if ($for_insert) {
      // 新規追加処理のSQLを生成
      $sql = "insert into product (category, name, price, detail) values (@category, @name, @price, @detail)";
    } else {
      // 更新処理のSQLを生成
      $sql = "update product set category = @category, name = @name, price = @price, detail = @detail where id = @id";
    }
    // プレースホルダ文字列に置換
    $sql = str_replace("@id", self::PLACEHOLDER_ID, $sql);
    $sql = str_replace("@category", self::PLACEHOLDER_CATEGORY, $sql);
    $sql = str_replace("@name", self::PLACEHOLDER_NAME, $sql);
    $sql = str_replace("@price", self::PLACEHOLDER_PRICE, $sql);
    $sql = str_replace("@detail", self::PLACEHOLDER_DETAIL, $sql);
    return $sql;
  }
 
}