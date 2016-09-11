<?php

/**
*
* @package phpBB Extension - Multi Ranks
* @copyright (c) 2015 gabriel - http://www.godfathertalks.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gabriel\medals\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_MDLS')),
			array('module.add', array(
				'acp', 'ACP_MDLS',	array(
					'module_basename'	=> '\gabriel\medals\acp\medals_module',
					'modes'				=> array('manage'),
				),
			)),
		);
	}

	public function update_schema()
	{
		return array(
			'add_columns'		=> array(
				$this->table_prefix . 'users'		=> array(
					'medal_one'		=> array('UINT', 0, 'after' => 'user_rank_three'),
					'medal_two'		=> array('UINT', 0, 'after' => 'user_rank_three'),
					'medal_three'	=> array('UINT', 0, 'after' => 'user_rank_three'),
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_columns'		=> array(
				$this->table_prefix . 'users'		=> array(
					'medal_one',
					'medal_two',
					'medal_three',
				),
			),
		);
	}
}
