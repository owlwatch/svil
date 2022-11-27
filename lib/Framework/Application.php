<?php

namespace Svil\Framework;

use DI\Container;
use DI\ContainerBuilder;

use Svil\Framework\MissingContainerException;
use Svil\View\RendererInterface;

abstract class Application
{

	/**
	 * The DI container
	 *
	 * @var DI\Container
	 */
	protected $container;

	public function configure(array $configs = [])
	{
		$builder = new ContainerBuilder();
		$builder->useAnnotations(false);
		foreach (func_get_args() as $config) {
			$builder->addDefinitions($config);
		}
		$this->container = $builder->build();
		return $this;
	}

	/**
	 * Returns an entry of the container by its name.
	 *
	 * @param string $name Entry name or a class name.
	 *
	 * @throws DependencyException Error while resolving the entry.
	 * @throws NotFoundException No entry found for the given name.
	 * @throws MissingContainerException The container was not created with the `configure` function
	 * @return mixed
	 */
	public function get($name)
	{
		if (!$this->container) {
			throw new MissingContainerException();
		}
		return $this->container->get($name);
	}

	/**
	 * Call the given function using the given parameters.
	 *
	 * Missing parameters will be resolved from the container.
	 *
	 * @param callable $callable   Function to call.
	 * @param array    $parameters Parameters to use. Can be indexed by the parameter names
	 *                             or not indexed (same order as the parameters).
	 *                             The array can also contain DI definitions, e.g. DI\get().
	 *
	 * @return mixed Result of the function.
	 */
	public function call($callable, array $parameters = [])
	{
		return $this->container->call($callable, $parameters);
	}

	public function template( $name, $context=[], $return=false )
	{
		return $this->call(function(RendererInterface $renderer) use($name, $context, $return){
			if( is_string( $name ) ){
				$name = [$name];
			}
			return $renderer->render( $name, $context, $return );
		});
	}

	public function create( $className )
	{
		foreach( func_get_args() as $_className ){
			$this->container->get( $_className );
		}
	}
}
