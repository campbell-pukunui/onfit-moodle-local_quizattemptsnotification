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
 
 /**
 * A simple cron function to check settings and automatically process.
 */
 
 function local_quizattemptsnotification_task() { 
    global $DB, $CFG;
    
    mtrace('Quiz Attempts Notification task started </br>');
    
    $cliemailaddress = get_config('local_quizattemptsnotification', 'cliemailaddress');
    
    $sql = "SELECT CONCAT(c.id,q.id,u.id) as uniqueid,c.shortname as coursename,q.name as quizname,CONCAT(u.firstname,' ',u.lastname) as username,gi.gradepass as gradepass,qg.grade as finalgrade, FROM_UNIXTIME(A.finishtime, '%d-%m-%Y %H:%i:%s') as finishtime, md.id as quizreviewattemptsid
            FROM {quiz} q
            JOIN
            (SELECT qa1.quiz, qa1.userid, SUM(1) AS Maxattempt, MAX(qa1.timefinish) as finishtime
             FROM {quiz_attempts} qa1
             WHERE qa1.state = 'finished'
             GROUP BY 1,2) AS A
             ON A.quiz = q.id
             AND A.maxattempt = q.attempts
             JOIN {grade_items} gi
             ON gi.iteminstance = q.id
             JOIN {quiz_grades} qg
             ON qg.quiz = q.id AND qg.userid = A.userid
             JOIN {course} c
             ON q.course = c.id
             JOIN {user} u
             ON A.userid = u.id
             JOIN
             (SELECT cm.id,cm.course
              FROM {course_modules} cm
              JOIN {modules} m
              ON cm.module = m.id
              WHERE m.name = 'quiz') as md
              ON md.course = c.id
             WHERE gi.itemmodule = 'quiz'
             AND (qg.grade < gi.gradepass OR gi.gradepass = 0)";
    $quizattemptresult = $DB->get_records_sql($sql);
    
    if($quizattemptresult) {
            mtrace('Emailing users');
           // From details.
           $from = get_admin();
          /* $from = new stdClass();
           $from->id        = 3;
           $from->username  = 'ken';
           $from->firstname = 'Admin';
           $from->lastname  = 'User';
           $from->alternatename = '';
           $from->middlename = '';
           $from->firstnamephonetic = '';
           $from->lastnamephonetic = '';
           $from->email     = 'noreply@pukunui.com';*/
        
           // To details.
           // id,username,lastname are doesnt matter because the function just needs those parameters to send email (so even its all fake value)
           $to = new stdClass();
           $to->id = 329;
           $to->username = "onfit";
           $to->firstname = "Onfit";
           $to->lastname  = "CCNotification";
           $to->alternatename = '';
           $to->middlename = '';
           $to->firstnamephonetic = '';
           $to->lastnamephonetic = '';
           $to->email     = $cliemailaddress;
           $to->maildisplay = 1;
           $to->mailformat  = 1;
    
           $emailsubject = 'Quiz Attempts Notification_'.date("d-m-Y H:i:s",time());
           
           $emailbody = 'Hi, this email is to inform you that those students below are not passing their quizzes.'."\r\n\r\n".
                        '[Information:]'."\r\n\r\n".
                        'ID'." | ".'Coursename'." | ".'Quizname'." | ".'Username'." | ".
                        'Gradepass'." | ".'Finalgrade'." | ".'Finishdate'." | ".'Assessment URL'."\r\n\r\n";
 
           foreach($quizattemptresult as $qz) {
               $emailbody .= $qz->uniqueid." | ".
                             $qz->coursename." | ".
                             $qz->quizname." | ".
                             $qz->username." | ".
                             $qz->gradepass." | ".
                             $qz->finalgrade." | ".
                             $qz->finishtime." | ".
                             $CFG->wwwroot.'/mod/quiz/report.php?id='.$qz->quizreviewattemptsid.'&mode=overview'."\r\n\r\n";
           }
           
           $emailbody .= '!! Those records were generated from system, please do not reply this email, thanks !!';
           // Send email.
           email_to_user($to, $from, $emailsubject, $emailbody);
    } else {
       //do nothing
    }
 }