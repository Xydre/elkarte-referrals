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
	$actionArray['referral'] = array('Referrals.controller.php', 'Referrals_Controller', 'action_index');
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
			WHERE id_member = {int:member_id}',
			array(
				'referred_by' => $referredBy,
				'date' => time(),
				'member_id' => $memberID,
			)
		);

	}
}

function ilcpf_referrals($memID, $area)
{
	global $context;

	if ($area != 'register' || !isset($_SESSION['referred_by']) || empty($_SESSION['referred_by']))
		return;

	$db = database();

	$request = $db->query('', '
		SELECT real_name FROM {db_prefix}members
		WHERE id_member = {int:member_id}',
		array(
			'member_id' => $_SESSION['referred_by'],
		)
	);
	$member = $db->fetch_assoc($request);
	$db->free_result($request);

	$context['custom_fields'][] = array(
			'name' => 'Referred By',
			'type' => 'text',
			'input_html' => '<input type="text" value="'.$member['real_name'].'" name="customfield[referred_by]" readonly />',
			'colname' => 'referred_by',
			'value' => $_SESSION['referred_by'],
			'show_reg' => 2,
	);
	$context['custom_fields_required'] = true;
}
