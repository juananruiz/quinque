<?php

$finder = PhpCsFixer\Finder::create()
	->in(__DIR__)
	->name('ConvocatoriaController.php');

return (new PhpCsFixer\Config())
	->setRules([
		'indentation_type' => true,
		'indent_size' => 4,
	])
	->setFinder($finder);
