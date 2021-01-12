<?php
namespace Svil\Component;

trait VueTrait
{

	public function render( $config=[], $return=false )
	{
		$config = $this->getConfig($config);
		$context = $this->getTemplateVariables($config);
		$context['id'] = $this->template . '-' . ($this->count++);
		ob_start();
		?>
		<div
			data-vue-component="<?php echo $this->getDashedName(); ?>"
			data-vue-props="<?php echo esc_attr( json_encode( $context ) ) ?>"
		></div>
		<?php
		$output = ob_get_clean();
		if( $return ){
			return $output;
		}
		echo $output;
		return;
	}
}