<?php
/**
 * BootStrap
 */
require "../lib/silex.phar";
require "../model/ActiveRecord.php";
require "../model/Player.php";
require "../model/Punche.php";

use Silex\Application;

$app = new Application();
$app->register(new Silex\Extension\TwigExtension(), array(
	'twig.path'       => __DIR__ . '/../templates',
	'twig.class_path' => __DIR__ . '/../lib/twig/lib'
));
$app->register(new Silex\Extension\SessionExtension());
/**
 * End of BootStrap
 */

/**
 * Home Logic
 */
$app->get('/', function() use ($app) {
	$players = array();
	foreach (Player::getAll() as $player) {
		$players[] = array(
			'obj' => $player,
			'life' => Punche::getPlayerLife($player)
		);
	}

	return $app['twig']->render(
		'home.html',
		array(
			'players' => $players,
			'loggedUser' => Player::isLogged()
		)
	);
});

/**
 * /player
 */
$app->get('/player/login', function() use ($app) {
	$to_view = array();
	if ($error = $app['session']->get('error')) {
		$to_view['error'] = $error;
	}
	return $app['twig']->render('login.html', $to_view);
});

$app->post('/player/login', function() use ($app) {
	$request = $app['request'];
	$login = $request->get('login');
	$senha = $request->get('senha');
	if (Player::doLogin($login, $senha)) {
		return $app->redirect('/');
	} else {
		$app['session']->set('error', array('message' => 'Login incorreto'));
		return $app->redirect('/player/login');
	}
});

/**
 * /punch
 */
$app->get('/punch/{punched}', function($punched) use ($app) {
	$vitima = Player::getOne($punched);
	$player = Player::isLogged();
	if ($player && (Punche::getPlayerLife($vitima) > 0)) {
		$punch = new Punche();
		$punch->puncher = $player->id;
		$punch->punched = $punched;
		$punch->datahora = new DateTime();
		$punch->save();
	}

	return $app->redirect('/');
});

$app->run();
?>
