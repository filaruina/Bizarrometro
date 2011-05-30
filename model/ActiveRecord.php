<?php
abstract class ActiveRecord {
	private $id;
	private $className;
	private $tableName;

	public static function getPDO() {
		try {
			$pdo = new PDO(
				'mysql:dbname=bizarrometro;host=localhost', 
				'root', 
				'123456'
			);
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}

		return $pdo;
	}

	public static function getOne($id) {
		$classe = get_called_class();
		$tableName = lcfirst($classe) . 's';
		$query = "SELECT * FROM " . $tableName . " WHERE id = " . $id;
		$statement = ActiveRecord::getPDO()->query($query);
		$statement->setFetchMode(PDO::FETCH_CLASS, $classe);

		return $statement->fetch();
	}

	public static function getAll() {
		$classe = get_called_class();
		$tableName = lcfirst($classe) . 's';
		$query = "SELECT * FROM " . $tableName;
		$statement = ActiveRecord::getPDO()->query($query);
		$statement->setFetchMode(PDO::FETCH_CLASS, $classe);

		$return = array();
		while ($obj = $statement->fetch()) {
			$return[] = $obj;
		}
		return $return;
	}

	public function save() {
		$classe = get_called_class();
		$tableName = lcfirst($classe) . 's';
		$query = "INSERT INTO " . $tableName . " VALUES (null, ";

		/**
		 * The class members come with an * in front of them since they are protected
		 * Have to clean that up and prepare the statement
		 */
		$dadosClasse = array();
		foreach ((array)$this as $campo => $valor) {
			if (preg_match('/\*/', $campo)) {
				$campoLimpo = preg_replace('/\*/', '', $campo);
				$dadosClasse[] = $valor;
				$query .= '?,';
			}
		}
		$query = substr($query, 0, -1) . ")";
		$statement = ActiveRecord::getPDO()->prepare($query);
		return $statement->execute($dadosClasse);
	}

	public function __get($name) {
		return $this->$name;
	}

	public function __set($name, $value) {
		if ($name == 'senha') {
			$value = md5($value);
		} else if ($value instanceof DateTime) {
			$value = $value->format('Y-m-d H:i:s');
		}

		$this->$name = $value;
	}

	public function __isset($name) {
		if (isset($this->$name)) {
			return true;
		}

		return false;
	}
}
