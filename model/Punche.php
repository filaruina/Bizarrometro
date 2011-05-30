<?php
class Punche extends ActiveRecord {
	protected $puncher;
	protected $punched;
	protected $datahora;

	public static function getPlayerLife($player) {
		$pdo = ActiveRecord::getPDO();
		//Um dia
		$query = "SELECT COUNT(*) FROM punches 
			WHERE punched = " . $player->id . "
			AND date(datahora) = curdate()";
		$quantidade = $pdo->query($query, PDO::FETCH_COLUMN, 0)->fetch();
		return 10 - $quantidade;
	}
}
