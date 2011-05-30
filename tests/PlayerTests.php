<?php
require "../model/ActiveRecord.php";
require "../model/Player.php";
class PlayerTest extends PHPUnit_Framework_TestCase {
	public function testEmptyPlayerInstance() {
		$player = new Player();
		$this->assertInstanceOf('Player', $player);
	}

	public function testFullPlayerInstance() {
		$player = Player::getOne(1);
		$this->assertEquals('1', $player->id);
		$this->assertEquals('Searle', $player->nome);
		$this->assertEquals('searle', $player->login);
		$this->assertEquals('fa0ac2e1847190f21a64abb86a4416eb', $player->senha);
	}

	public function testeGetAllPlayers() {
		$players = Player::getAll();
		$this->assertEquals(10, count($players));
	}

	/*
	public function testSavePlayer() {
		$player = new Player();
		$player->nome = "Filipao";
		$player->login = "Wuou";
		$player->senha = "senha";
		$this->assertEquals(true, $player->save());
	}*/
}
