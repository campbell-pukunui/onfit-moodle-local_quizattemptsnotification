<?php
/**
 * Automate CSV user attempts to quiz with cron process
 *
 * Admin settings
 *
 * @package    local
 * @subpackage quizattemptsnotification
 * @author     Ken Chang <kenc@pukunui.com>, Pukunui
 * @copyright  2018 onwards, Pukunui
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->dirroot.'/'.$CFG->admin.'/tool/uploaduser/locallib.php');
// No external navigation as there is no frontend for this plugin.

// Capability check to filter the users.
if (has_capability('local/update:settings', context_system::instance())) {

  $settings = new admin_settingpage('local_quizattemptsnotification_settings', 
  new lang_string('pluginname', 'local_quizattemptsnotification'), 
  'local/update:settings');
  
    $settings->add(new admin_setting_configtext(
                'local_quizattemptsnotification/cliemailaddress',
                new lang_string('cliemailaddress', 'local_quizattemptsnotification'),
                '',
                get_string('directemailaddress', 'local_quizattemptsnotification'),
                PARAM_NOTAGS,
                50
                ));
    $admin = get_admin();
    $ADMIN->add('localplugins', $settings);
}