<?php
/**
 *
 * Scheduled task Definition
 *
 * @package   local_quizattemptsnotification
 * @author    Ken Chang <kenc@pukunui.com>, Pukunui
 * @copyright 2018 onwards, Pukunui
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$tasks = array(
             array(
                 'classname' => 'local_quizattemptsnotification\task\notify',
                 'blocking'  => 0,
                 'minute'    => '0',
                 'hour'      => '*',
                 'day'       => '*',
                 'dayofweek' => '*',
                 'month'     => '*'
             )
         );