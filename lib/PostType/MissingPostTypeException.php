<?php
namespace Svil\PostType;

use Exception;

class MissingPostTypeException extends Exception
{
    public function __construct()
	{
		parent::__construct('Missing PostType constant');
	}
}