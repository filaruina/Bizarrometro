<?php
require "../model/ActiveRecord.php";
require "../model/Player.php";
require "../model/Punche.php";
class PucheTest extends PHPUnit_Framework_TestCase {
	public function testEmptyPuncheInstance() {
		$punch = new Punche();
		$this->assertInstanceOf('Punche', $punch);
	}

	public function testFullPuncheInstance() {
		$punch = Punche::getOne(1);
		$this->assertEquals('1', $punch->id);
		$this->assertEquals('1', $punch->puncher);
		$this->assertEquals('2', $punch->punched);
		$this->assertEquals('2011-04-15 16:09:59', $punch->datahora);
	}

	public function testSavePunche() {
		$punch = new Punche();
		$punch->puncher = 1;
		$punch->punched = 2;
		$punch->datahora = new DateTime();
		$this->assertEquals(true, $punch->save());
	}

	public function testPlayer2Life() {
		$player = Player::getOne(2);
		$life = Punche::getPlayerLife($player);
		$this->assertEquals(6, $life);
	}
}
