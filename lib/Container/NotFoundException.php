<?php
namespace Svil\Container;

use Psr\Container\NotFoundExceptionInterface;
use Exception;

/**
 * Exception thrown when a class or a value is not found in the container.
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}