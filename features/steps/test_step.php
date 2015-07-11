<?php
use Behat\Behat\Context\Step\Given,
	Behat\Behat\Context\Step\When,
	Behat\Behat\Context\Step\Then;

$steps->When('テストページ$/', function($world, $page) {
	return [
		new When('"' . "/test.php" . '" へ移動する'),
	];
});
$steps->Given('/^テストページ$/', function($world) {
    throw new \Behat\Behat\Exception\PendingException();
});