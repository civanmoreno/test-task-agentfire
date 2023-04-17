<?php

declare( strict_types=1 );

namespace AgentFire\Plugin\Task;

use Twig\{Environment, Loader};
use AgentFire\Plugin\Task\Traits\Singleton;

/**
 * Class Template
 * @package AgentFire\Plugin\Task
 *
 * Usage example: Template::getInstance()->display( 'main.twig' );
 */
class Template {
	use Singleton;

	/**
	 * @var Environment
	 */
	private $twig;

	/**
	 * Template constructor.
	 */
	public function __construct() {
		$this->twig = new Environment(
			new Loader\FilesystemLoader( AGENTFIRE_TEST_PATH . 'template/task' )
		);
	}

	/**
	 * @param string $template
	 * @param array $atts
	 *
	 * @return string
	 * @throws Exception\Template
	 */
	public function render( string $template, array $atts = [] ): string {
		try {
			$result = $this->twig->render( $template, $atts );
		} catch ( \Exception $e ) {
			throw new Exception\Template( $e->getMessage(), $e->getCode() );
		}

		return $result;
	}

	/**
	 * @param string $template
	 * @param array $atts
	 *
	 * @throws Exception\Template
	 */
	public function display( string $template, array $atts = [] ) {
		echo $this->render( $template, $atts );
	}
}