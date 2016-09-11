<?php

/**
*
* @package Medals
* @copyright (c) 2016 Gabriel
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gabriel\medals\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/

class listener implements EventSubscriberInterface
{
	/* @var \phpbb\template\template */
	protected $template;
	/* @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var String phpBB Root path */
	protected $phpbb_root_path;

	/**
	* Constructor
	*
	* @param \phpbb\template					$template	Template object
	* @param \phpbb\db\driver\driver_interface	$db			Database
	* @param \phpbb\config\config				$config		Config
	* @param String								$phpbb_root_path	phpBB Root path
	*/
	public function __construct(\phpbb\template\template $template, \phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, $phpbb_root_path)
	{
		$this->template = $template;
		$this->db = $db;
		$this->config = $config;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.memberlist_view_profile'			=> 'medals_on_profile',
			'core.viewtopic_modify_post_row'		=> 'medals_on_topic',
			'core.ucp_pm_view_messsage'				=> 'medals_on_pm',
		);
	}

	public function medals_on_profile($event)
	{
		$medal1 = (int) $event['member']['medal_one'];
		$medal2 = (int) $event['member']['medal_two'];
		$medal3 = (int) $event['member']['medal_three'];

		// Grab the additional medal data
		$sql = 'SELECT *
				FROM ' . RANKS_TABLE . '
				WHERE ' . $this->db->sql_in_set('rank_id', array($medal1, $medal2, $medal3));
		$result = $this->db->sql_query($sql);

		// Set up user medal array using the rank-type medals
		$rank = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$rank[$row['rank_id']]['title'] = $row['rank_title'];
			$rank[$row['rank_id']]['src'] = (!empty($row['rank_image'])) ? $this->phpbb_root_path . $this->config['ranks_path'] . '/' . $row['rank_image'] : '';
			$rank[$row['rank_id']]['img'] = (!empty($row['rank_image'])) ? '<img src="' . $rank[$row['rank_id']]['src'] . '" alt="' . $row['rank_title'] . '" title="' . $row['rank_title'] . '" />' : '';
		}

		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'MEDAL_ONE_TITLE'	=> $medal1 ? $rank[$medal1]['title'] : '',
			'MEDAL_ONE_IMG'		=> $medal1 ? $rank[$medal1]['img'] : '',
			'MEDAL_TWO_TITLE'	=> $medal2 ? $rank[$medal2]['title'] : '',
			'MEDAL_TWO_IMG'		=> $medal2 ? $rank[$medal2]['img'] : '',
			'MEDAL_THR_TITLE'	=> $medal3 ? $rank[$medal3]['title'] : '',
			'MEDAL_THR_IMG'		=> $medal3 ? $rank[$medal3]['img'] : '',
		));
	}

	public function medals_on_topic($event)
	{
		$sql = 'SELECT r.*, u.medal_one, u.medal_two, u.medal_three
				FROM ' . RANKS_TABLE . ' r
				LEFT JOIN ' . USERS_TABLE . ' u
					ON r.rank_id = u.medal_one
						OR r.rank_id = u.medal_two
							OR r.rank_id = u.medal_three
				WHERE u.user_id = ' . (int) $event['poster_id'];
		$result = $this->db->sql_query($sql);

		// Set up user medal array using the rank-type medals
		$rank = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Define medal order
			$medal1 = (int) $row['medal_one'];
			$medal2 = (int) $row['medal_two'];
			$medal3 = (int) $row['medal_three'];

			$rank[$row['rank_id']]['title'] = $row['rank_title'];
			$rank[$row['rank_id']]['src'] = (!empty($row['rank_image'])) ? $this->phpbb_root_path . $this->config['ranks_path'] . '/' . $row['rank_image'] : '';
			$rank[$row['rank_id']]['img'] = (!empty($row['rank_image'])) ? '<img src="' . $rank[$row['rank_id']]['src'] . '" alt="' . $row['rank_title'] . '" title="' . $row['rank_title'] . '" />' : '';
		}

		$this->db->sql_freeresult($result);

		$event['post_row'] = array_merge($event['post_row'], array(
			'MEDAL_ONE_TITLE'	=> $medal1 ? $rank[$medal1]['title'] : '',
			'MEDAL_ONE_IMG'		=> $medal1 ? $rank[$medal1]['img'] : '',
			'MEDAL_TWO_TITLE'	=> $medal2 ? $rank[$medal2]['title'] : '',
			'MEDAL_TWO_IMG'		=> $medal2 ? $rank[$medal2]['img'] : '',
			'MEDAL_THR_TITLE'	=> $medal3 ? $rank[$medal3]['title'] : '',
			'MEDAL_THR_IMG'		=> $medal3 ? $rank[$medal3]['img'] : '',
		));
	}

	public function medals_on_pm($event)
	{
		$medal1 = (int) $event['user_info']['medal_one'];
		$medal2 = (int) $event['user_info']['medal_two'];
		$medal3 = (int) $event['user_info']['medal_three'];

		// Grab the medal data
		$sql = 'SELECT *
				FROM ' . RANKS_TABLE . '
				WHERE ' . $this->db->sql_in_set('rank_id', array($medal1, $medal2, $medal3));
		$result = $this->db->sql_query($sql);

		// Set up user medal array using the rank-type medals
		$rank = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$rank[$row['rank_id']]['title'] = $row['rank_title'];
			$rank[$row['rank_id']]['src'] = (!empty($row['rank_image'])) ? $this->phpbb_root_path . $this->config['ranks_path'] . '/' . $row['rank_image'] : '';
			$rank[$row['rank_id']]['img'] = (!empty($row['rank_image'])) ? '<img src="' . $rank[$row['rank_id']]['src'] . '" alt="' . $row['rank_title'] . '" title="' . $row['rank_title'] . '" />' : '';
		}

		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'MEDAL_ONE_TITLE'	=> $medal1 ? $rank[$medal1]['title'] : '',
			'MEDAL_ONE_IMG'		=> $medal1 ? $rank[$medal1]['img'] : '',
			'MEDAL_TWO_TITLE'	=> $medal2 ? $rank[$medal2]['title'] : '',
			'MEDAL_TWO_IMG'		=> $medal2 ? $rank[$medal2]['img'] : '',
			'MEDAL_THR_TITLE'	=> $medal3 ? $rank[$medal3]['title'] : '',
			'MEDAL_THR_IMG'		=> $medal3 ? $rank[$medal3]['img'] : '',
		));
	}
}
