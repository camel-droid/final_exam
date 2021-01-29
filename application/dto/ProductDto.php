<?php

/**
 * 商品を管理するクラス：productテーブルの1レコードを管理するクラス
 */
class ProductDTO {
  /**
   * プロパティ
   */
  private $id;        // 商品ID
  private $category;  // 商品カテゴリ
  private $name;      // 商品名
  private $price;     // 価格
  private $detail;    // 商品説明
  
  /**
   * コンストラクタ
   * @param int     商品ID
   * @param string  商品カテゴリ
   * @param string  商品名
   * @param int     価格
   * @param string  商品説明
   */
  function __construct(int $id, string $category, string $name, int $price, string $detail) {
    $this->id = $id;
    $this->category = $category;
    $this->name = $name;
    $this->price = $price;
    $this->detail = $detail;
  }
  
  /** アクセサメソッド群 */
  
  function setId(int $id):void {
    $this->id = $id;
  }
  
  function getId():int {
    return $this->id;
  }
  
  function setCategory(string $category):void {
    $this->category = $category;
  }
  
  function getCategory():string {
    return $this->category;
  }
  
  function setName(string $name):void {
    $this->name = $name;
  }
  
  function getName():string {
    return $this->name;
  }
  
  function setPrice(int $price):void {
    $this->price = $price;
  }
  
  function getPrice():int {
    return $this->price;
  }
  
  function setDetail(string $detail):void {
    $this->detail = $detail;
  }
  
  function getDetail():string {
    return $this->detail;
  }
  
  /**
   * シリアライズ化
   * @return string シリアライズ化された文字列：書式「[プロパティ名]プロパティ値」を連結文字「:」で連結した文字列
   */
  function toString():string {
    $toString = "";
    $toString .= "[id]{$this->id}:";
    $toString .= "[category]{$this->category}:";
    $toString .= "[name]{$this->name}:";
    $toString .= "[price]{$this->price}:";
    $toString .= "[detail]{$this->detailid}";
    return $toString;
  }
  
}