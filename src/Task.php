<?php

declare( strict_types=1 );

namespace AgentFire\Plugin;

use AgentFire\Plugin\Task\AdminPage;
use AgentFire\Plugin\Task\Marks;
use AgentFire\Plugin\Task\RestApi;
use AgentFire\Plugin\Task\ShortCodes;
use AgentFire\Plugin\Task\Traits\Singleton;

/**
 * Class Task
 * @package AgentFire\Plugin\Task
 */
class Task {
	use Singleton;

	public function __construct() {
		AdminPage::getInstance();
		ShortCodes::getInstance();
		RestApi::getInstance();
		Marks::getInstance();
	}
}
