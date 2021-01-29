<?php
class DAO {
	/**
	 * クラス定数：データベース接続情報文字列
	 */
	const DB_DSN = "mysql:host=localhost;dbname=productdb;charset=utf8;port=3306";
	const DB_USR = "productdb_admin";
	const DB_PWD = "admin123";

	/**
	 * プロパティ
	 */
	private $pdo;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		try {
			$this->pdo = new PDO(self::DB_DSN, self::DB_USR, self::DB_PWD);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}
}