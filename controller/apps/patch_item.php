<?php
namespace Core;
use Exception;

if (!defined('__GOOSE__')) exit();

/**
 * edit app
 *
 * @var Goose $this
 */

try
{
	// check srl
	if (!((int)$this->params['srl'] && $this->params['srl'] > 0))
	{
		throw new Exception('Not found srl', 500);
	}

	// get values
	$_PATCH = Util::getFormData();

	// id check
	if ($_PATCH['id'] && !Util::allowString($_PATCH['id']))
	{
		throw new Exception('`id` can be used only in numbers and English.');
	}

	// check authorization
	$token = Auth::checkAuthorization($this->level->admin);

	// set output
	$output = Controller::edit((object)[
		'goose' => $this,
		'table' => 'app',
		'srl' => (int)$this->params['srl'],
		'data' => [
			$_PATCH['id'] ? "id='$_PATCH[id]'" : '',
			$_PATCH['name'] ? "name='$_PATCH[name]'" : '',
		],
	]);

	// set token
	if ($token) $output->_token = $token;

	// output data
	Output::data($output);
}
catch (Exception $e)
{
	Error::data($e->getMessage(), $e->getCode());
}