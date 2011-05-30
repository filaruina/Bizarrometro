<?php
class Player extends ActiveRecord {
	protected $nome;
	protected $login;
	protected $senha;

	public static function doLogin($login, $senha) {
		$pdo = ActiveRecord::getPDO();
		$query = "SELECT * FROM players
			WHERE login = '" . $login . "'
			AND senha = md5('" . $senha . "')
			LIMIT 1";
		$obj = $pdo->query($query, PDO::FETCH_CLASS, 'Player', array())->fetch();
		if ($obj) {
			$_SESSION['usuario'] = serialize($obj);
			return $obj;
		} else {
			return false;
		}
	}

	public static function isLogged() {
		if (isset($_SESSION['usuario'])) {
			$obj = unserialize($_SESSION['usuario']);
			if ($obj) {
				return $obj;
			}
		}
		return false;
	}
}
