<?php
global $DB , $USER , $CFG ;
defined('MOODLE_INTERNAL') || die() ;
require(dirname(__FILE__) . '/../../config.php');
require($CFG->libdir . '/completionlib.php') ;

function send_mail(){
    global $DB , $USER , $CFG ;

    $mdl_user = $DB->get_records_sql("SELECT ue.id,ue.timeend,c.fullname,c.id as courseid,u.firstname,u.lastname,u.email,u.id as userid,ue.timestart from {user_enrolments} ue
    INNER JOIN {enrol} e on e.id=ue.enrolid 
    INNER JOIN {user} u on u.id=ue.userid
    INNER JOIN {course} c on c.id=e.courseid WHERE u.id = $USER->id ORDER BY ue.timestart DESC");

    foreach($mdl_user as $keyvalue){
        
    $course_object = $DB->get_record('course', array('id' => $keyvalue->courseid));
    $cinfo = new completion_info($course_object);
    print_r($cinfo);
    $iscomplete = $cinfo->is_course_complete($USER->id);
    if ($iscomplete == true) {

      $subject = ' Test Mail';
      $fullmessage = 'Only For Testing';
      $fullmessagehtml = html_to_text('This Is Only For Testing');
      $user = $DB->get_record('user', array('id' => 23));
      $fromuser = $DB->get_record('user', array('id' => 2));
      $sucess = email_to_user($user, $fromuser, $subject, $fullmessage, $fullmessagehtml);
      if ($sucess) {
        echo "hii";
      } else {
        echo "bye";
      }
    } else {
      echo "bye....";
    }
  }
}
