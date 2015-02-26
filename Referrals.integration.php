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

function ia_referrals(&$actionArray, &$adminActions)
{
	$actionsArray['referral'] = array('Referrals.controller.php', 'Referrals_Controller', 'action_index');
}

function ira_referrals($regOptions, $memberID)
{
	if (isset($_SESSION['referred_by']))
	{
		$referredBy = $_SESSION['referred_by'];
		$db = database();

		$db->query('', '
			UPDATE {db_prefix}members
			SET referrals = referrals + 1
			WHERE id_member = {int:referred_by}',
			array(
				'referred_by' => $referredBy,
			)
		);

		$db->query('', '
			UPDATE {db_prefix}members
			SET referred_by = {int:referred_by}, referred_date = {int:date}
			WHERE id_member = {int:member_id}'
			array(
				'referred_by' => $referredBy,
				'date' => time(),
				'member_id' => $memberID,
			)
		);

	}
}
