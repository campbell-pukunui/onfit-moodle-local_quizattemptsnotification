<?php
/**
 *
 * Class definition for schedule task
 *
 * @package   local_quizattemptsnotification
 * @author    Ken Chang <kenc@pukunui.com>, Pukunui
 * @copyright 2018 onwards, Pukunui
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_quizattemptsnotification\task;

//require_once($CFG->dirroot.'/local/movegroup/lib.php');
require_once($CFG->dirroot.'/local/quizattemptsnotification/lib.php');
/**
 * Extend core scheduled task
 */
class notify extends \core\task\scheduled_task {
    /**
     * Return name of the Task
     * 
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'local_quizattemptsnotification');
    }
    
    /**
     * Perform the task
     */
    public function execute() {
        local_quizattemptsnotification_task('auto');
    }
}