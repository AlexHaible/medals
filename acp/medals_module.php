<?php

/**
*
* @package Medals
* @copyright (c) 2016 Gabriel
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gabriel\medals\acp;

class medals_module
{
	public	$u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $request, $user;
		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('gabriel.medals.admin.controller');
		// Requests
		$action = $request->variable('action', '');

		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);

				$this->tpl_name = 'mdls_body';
				$this->page_title = $user->lang('ACP_MDLS');
				$admin_controller->edit_user_ranks();
	}
}
