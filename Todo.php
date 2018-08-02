<?php

// it is easy to make name by using "namespace"
namespace MyApp;

class Todo {
	private $_db;
	// インスタンスを生成するときの初期化 -> コンストラクタ
	public function __construct() {
	    $this->_createToken();
		try {
			// PHP データベース入門参照
			$this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
			$this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			$e->getMessage();
			exit;
		}
	}

//	CSFR対策
	private function _createToken() {
	    if( !isset($_SESSION['token']) ) {
	        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }
    }

	public function getAll() {
		$stmt = $this->_db->query("select * from todo_list order by id desc");
		return $stmt->fetchAll(\PDO::FETCH_OBJ);
	}

	public function post() {
	    $this->_validateToken();
		if(!isset($_POST["mode"])) {
			throw new Exception("Mode Not Set!");
		}
		switch ($_POST["mode"]) {
			case "update":
				return $this->_update();
			case "create":
				return $this->_create();
			case "delete":
				return $this->_delete();
		}
	}

    private function _validateToken() {
	    if (
	        !isset($_SESSION['token']) ||
            !isset($_POST['token']) ||
            $_SESSION['token'] != $_POST['token']
        ) {
	        throw new \Exception("invalid token!");
        }
    }

	private function _update() {
	    if(!isset($_POST["id"])){
	        throw new Excetion("[update] ID not set!");
        }

//      1回ずつ処理を行うため
        $this->_db->beginTransaction();

//      update db
        $sql = sprintf("update todo_list set state = (state + 1) %% 2 where id = %d", $_POST['id']);
	    $stmt = $this->_db->prepare($sql);
	    $stmt->execute();

//	    get data
        $sql = sprintf("select state from todo_list where id = %d", $_POST['id']);
        $stmt = $this->_db->query($sql);
        $state = $stmt->fetchColumn();

        $this->_db->commit();

        return [
          "state" => $state
        ];

	}

	public function _create() {
        if(!isset($_POST["title"]) || $_POST["title"] == "") {
            throw new Exception("[create] title not set!");
        }

//        delete
//        string なので placfolder
        $sql = "insert into todo_list (title) values (:title)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([":title"=>$_POST["title"]]);

        return [
            "id" => $this->_db->lastInsertId()
        ];
	}

	public function _delete() {
		if(!isset($_POST["id"])) {
		    throw new Exception("[delete] ID not set!");
        }

//        delete
        $sql = sprintf("delete from todo_list where id = %d", $_POST["id"]);
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();

		return [];
	}
}

?>