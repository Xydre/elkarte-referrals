<?php

/**
 * @package Referrals
 *
 * @author Antony Derham
 * @copyright 2015 Antony Derham
 *
 * @version 1.0
 */

if (!defined('ELK'))
	die('No access...');

/**
 * Referrals controller.
 */
class Referrals_Controller extends Action_Controller
{
	/*
	 * Incoming referral
	 */
	public function action_index()
	{
		if (!isset($_REQUEST['id']))
			redirectexit();

		if (!empty($_REQUEST['id']))
		{
			$referredBy = $_REQUEST['id'];
			$db = database();

			$db->query('', '
				UPDATE {db_prefix}members
				SET referral_link_hits = referral_link_hits + 1
				WHERE id_member = {int:referred_by}',
				array(
					'referred_by' => $referredBy,
				)
			);

			$_SESSION['referred_by'] = $referredBy;
		}

		redirectexit('action=register');
	}
}
