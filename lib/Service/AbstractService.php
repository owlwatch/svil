<?php
namespace Svil\Service;

class AbstractService
{
	public function getTemplateVariables(array $config = [])
	{
		return [
			'name' => $this->name,
			'props' => $config
		];
	}

	public function render( array $config )
	{
		?>
		<span 
			data-vue-component="<?php echo $config['name']; ?>"
			data-vue-props="<?php echo $config['props']; ?>"
		></span>
		<?php
	}
}