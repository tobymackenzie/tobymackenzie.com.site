<?php
namespace TJM\Bundle\StandardEditionBundle\Component\App;

define(__NAMESPACE__ . '\PROJECT_DIR', realpath(__DIR__ . '/../..'));
define(__NAMESPACE__ . '\VENDOR_DIR', constant(__NAMESPACE__ . '\PROJECT_DIR') . '/vendor');

return [
	'paths'=> [
		//--cli paths
		'PHPCLI'=> '/usr/bin/php'
		//--symfony paths
		,'app'=> constant(__NAMESPACE__ . '\PROJECT_DIR') . '/app'
		,'project'=> constant(__NAMESPACE__ . '\PROJECT_DIR')
		,'src'=> constant(__NAMESPACE__ . '\PROJECT_DIR') . '/src'
		,'var'=> constant(__NAMESPACE__ . '\PROJECT_DIR') . '/var'
		,'vendor'=> constant(__NAMESPACE__ . '\VENDOR_DIR')
	]
];
