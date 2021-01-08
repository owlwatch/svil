<?php

namespace Svil\Container;

use Psr\Container\ContainerInterface;

use Traversable;
use ArrayAccess;
use Closure;
use ReflectionClass;

use Svil\Container\NotFoundException;
use Svil\Container\ContainerException;


class Container implements ContainerInterface, ArrayAccess
{

	private $instances = [];

	/**
	 * @param      $abstract
	 * @param null $concrete
	 */
	public function set($abstract, $concrete = null)
	{
		if ($concrete === NULL) {
			$concrete = $abstract;
		}
		$this->instances[$abstract] = $concrete;
	}

	public function unset( $dependency )
	{
		unset($this->instances[$dependency]);
	}

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $abstract Identifier of the entry to look for.
	 *
	 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
	 * @throws ContainerExceptionInterface Error while retrieving the entry.
	 *
	 * @return mixed Entry.
	 */
	public function get($abstract)
	{
		// if we don't have it, just register it
		if (!isset($this->instances[$abstract])) {
			$this->set($abstract);
		}

		return $this->resolve($this->instances[$abstract], $parameters);
	}

	/**
	 * resolve single
	 *
	 * @param $concrete
	 * @param $parameters
	 *
	 * @return mixed|object
	 * @throws Exception
	 */
	public function resolve($concrete, $parameters)
	{
		if ($concrete instanceof Closure) {
			return $concrete($this, $parameters);
		}

		$reflector = new ReflectionClass($concrete);
		// check if class is instantiable
		if (!$reflector->isInstantiable()) {
			throw new NotFoundExceptionInterface("Class {$concrete} is not instantiable");
		}

		// get class constructor
		$constructor = $reflector->getConstructor();
		if (is_null($constructor)) {
			// get new instance from class
			return $reflector->newInstance();
		}

		// get constructor params
		$parameters   = $constructor->getParameters();
		$dependencies = $this->getDependencies($parameters);

		// get new instance with dependencies resolved
		return $reflector->newInstanceArgs($dependencies);
	}

	/**
	 * get all dependencies resolved
	 *
	 * @param $parameters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getDependencies( Traversable $parameters)
	{
		$dependencies = [];
		foreach ($parameters as $parameter) {
			// get the type hinted class
			$dependency = $parameter->getClass();
			if ($dependency === NULL) {
				// check if default value for a parameter is available
				if ($parameter->isDefaultValueAvailable()) {
					// get default value of parameter
					$dependencies[] = $parameter->getDefaultValue();
				} else {
					throw new ContainerException("Can not resolve class dependency {$parameter->name}");
				}
			} else {
				// get dependency resolved
				$dependencies[] = $this->get($dependency->name);
			}
		}

		return $dependencies;
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $dependency Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has($dependency)
	{
		return isset($this->instances[$dependency]);
	}


	public function offsetExists($offset)
	{
		return $this->has($offset);
	}

	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	public function offsetSet($offset, $value)
	{
		if (is_null($offset)) {
			$this->set($value);
		}
		else {
			$this->set($offset, $value);
		}
	}

	public function offsetUnset($offset)
	{
		$this->unset($offset);
	}
}
