<?php

/**
*
* @package Medals
* @copyright (c) 2016 Gabriel
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gabriel\medals\acp;

class medals_info
{
	function module()
	{
		return array(
			'filename'	=> '\gabriel\medals\acp\medals_module',
			'title'	=> 'ACP_MDLS',
			'version'	=> '1.0.0',
			'modes'	=> array(
				'manage'		=> array('title' => 'ACP_MDLS', 'auth' => 'ext_gabriel/medals && acl_a_board', 'cat' => array('ACP_MDLS')),
			),
		);
	}
}
