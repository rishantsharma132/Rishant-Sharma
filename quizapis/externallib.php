<?php
// This file is part of Creolms - http://creolms.org/
//
// Creolms is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Creolms is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Creolms.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External files API
 *
 * @package    core_files
 * @category   external
 */

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/externallib.php');

class local_quizapis_external extends external_api {
    public static function get_quiz_questions_parameters() {
        return new external_function_parameters (
            array(
                'moduleid' => new external_value(PARAM_INT, 'Course module id')
            )
        );
    }
    public static function get_quiz_questions_returns() {
        return new external_single_structure(
                array(
                 'quizname' => new external_value(PARAM_RAW, 'name of quiz'),                 
                 'questionlist' => new external_multiple_structure(
                        new external_single_structure(
                           array(
                                'questionid' => new external_value(PARAM_INT, 'questionid.'),
                                'questionposition' => new external_value(PARAM_INT, 'Total files size.')
                              ), 'Contents summary information.', VALUE_OPTIONAL 
                            ), 'Contents summary information.', VALUE_OPTIONAL
                    )
                                      
                )           
        );
    }
   public static function get_quiz_questions($moduleid) {         
        global $CFG, $DB, $USER;       
        $ques_field = array();
        $getquiz = $DB->get_record('course_modules', array('id' => $moduleid));
        $quizname = $DB->get_record('quiz', array('id' => $getquiz->instance));

        $questions = $DB->get_records_sql("SELECT slot, questionid FROM {quiz_slots} WHERE quizid = '$getquiz->instance'");
        $information['quizname'] = $quizname->name;
        foreach ($questions as $question) {
            $ques_field[] = array(           
                'questionid' => $question->questionid,
                'questionposition' => $question->slot,
            );         

        }
        $information['questionlist'] = $ques_field; 
       
        return $information;
        //return json_encode($return, JSON_UNESCAPED_SLASHES);
    }

   public static function get_questions_details_parameters() {
        return new external_function_parameters (
            array(
                'questionid' => new external_value(PARAM_INT, 'question id')
            )
        );
    }
    public static function get_questions_details_returns() {
        return new external_single_structure(
                array(  

                    'questionname' => new external_value(PARAM_RAW, 'name of question'),                 
                    'questiontext' => new external_value(PARAM_RAW, ' text of question'),
                    'questionstatus' => new external_value(PARAM_RAW, ' status of question'), 

                    'options' => new external_multiple_structure(
                            new external_single_structure(
                            array( 
                                'qanswer' => new external_value(PARAM_RAW, ' answer of question'),                  
                                'qfraction' => new external_value(PARAM_RAW, ' fraction of question'),     
                                'qfeedback' => new external_value(PARAM_RAW, ' feedback of question'), 

                                )
                            )
                       ) 
                )    
        );
    }
    public static function get_questions_details($questionid) {         
        global $CFG, $DB, $USER;   
        $information = array();    
        $op = array();

        $question = $DB->get_records_sql("SELECT qa.id, q.name, q.questiontext,qa.answer,qa.fraction, qa.feedback FROM {question} q INNER JOIN {question_answers} qa ON q.id = qa.question WHERE q.id = $questionid ");  
        foreach ($question as $keylue) {
            $questionname = $keylue->name;
            $questionquestiontext = $keylue->questiontext;
            $questionanswer = $keylue->answer;
            $questionfraction = $keylue->fraction;
            $questionfeedback = $keylue->feedback;
            $status = $DB->get_record_sql("SELECT status FROM {question_versions} WHERE questionid=$questionid"); 
             if ($status->status == 'ready') {
                $questionstatus = 'Active';    
            } elseif ($status->status == 'draft') {
                $questionstatus = 'In-active'; 
            }       
            $op[]= array(
                'qanswer'=>$questionanswer,  
                'qfraction'=>$questionfraction,
                'qfeedback'=>$questionfeedback                
  
            );       
        }
        $information= array(
            'questionname'=>$questionname,  
            'questiontext'=>$questionquestiontext,
            'questionstatus'=>$questionstatus,   
            'options' =>$op
        ); 
        return $information;
   } 
   public static function get_questions_category_parameters() {
        return new external_function_parameters (
            array(
                'categoryid' => new external_value(PARAM_INT, 'Question category id')
            )
        );
    }
    public static function get_questions_category_returns() {
        return new external_single_structure(
            array(
                'parentcategory' => new external_value(PARAM_RAW, 'name of question category'),                 
                'categorylist' => new external_multiple_structure(
                    new external_single_structure(
                        array(
        	             'categoryid' => new external_value(PARAM_INT, 'id of category'),                 
        	             'categoryname' => new external_value(PARAM_RAW, 'name of category'),  
                        ), 'Contents summary information.', VALUE_OPTIONAL 
                    ), 'Contents summary information.', VALUE_OPTIONAL  
                )              
            )
        );
    }
   public static function get_questions_category($categoryid) {         
        global $CFG, $DB, $USER;       
        $quescat = array();
        $pcatname = $DB->get_record('question_categories', array('id' => $categoryid));

        $parentcats = $DB->get_records_sql("SELECT id, name as names FROM {question_categories} WHERE parent = $categoryid ");
        $info['parentcategory'] = $pcatname->name;
        foreach ($parentcats as $kvalue) {
            $catid = $kvalue->id;
            $catname = $kvalue->names;        
            $quescat[] = array(
                'categoryid' => $catid,
                'categoryname' => $catname
            );
        } 
        $info['categorylist'] = $quescat; 
        return $info;
        } 

    public static function update_quizattempts_parameters() {
        return new external_function_parameters (
            array(
                'moduleid' => new external_value(PARAM_INT, 'Course module id'),
                'allowattempts' => new external_value(PARAM_INT, 'Allow attempts')
            )
        );
    }
    public static function update_quizattempts_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Status of request', VALUE_OPTIONAL),
            'message' => new external_value(PARAM_TEXT, 'Response message', VALUE_OPTIONAL),
        ]);
    }
   public static function update_quizattempts($moduleid, $allowattempts) {         
        global $CFG, $DB, $USER;   
        $getquizid = $DB->get_record('course_modules', array('id' => $moduleid)); 
        if($getquizid){   
	        $qdata = new stdClass();
	        $qdata->id = $getquizid->instance;
	        $qdata->attempts = $allowattempts;       
            $DB->update_record('quiz', $qdata);
            return ['success' => true, 'message' => 'The maximum number of attempts update succesfully.'];
        } else {
        	return ['success' => false, 'message' => 'Module id is not valid, Please enter valid Module id.'];
        }
    }
    public static function get_quizattempts_parameters() {
        return new external_function_parameters (
            array(
                'courseid' => new external_value(PARAM_INT, 'Course module id'),
                'quizid' => new external_value(PARAM_INT, 'quizid module id'),
                'userid' => new external_value(PARAM_INT, 'userid module id')
               
            )
        );
    }
    public static function get_quizattempts_returns() {
        return new external_single_structure([
            'startedon' => new external_value(PARAM_INT, 'name of quiz'),  
            'completedon' => new external_value(PARAM_RAW, 'completed of attempt'),
            'failedquestions' => new external_value(PARAM_RAW, 'failed questions'),
            'successfulquestions' => new external_value(PARAM_RAW, 'successful questions'),
            'grade' => new external_value(PARAM_RAW, 'Grade of attempt'),
            'totalattempts' => new external_value(PARAM_RAW, 'total attempts'),  
        ]);
    }
    public static function get_quizattempts($courseid, $quizid, $userid) {         
        global $CFG, $DB, $USER;   
        require_once($CFG->dirroot . '/mod/quiz/locallib.php');
        require_once($CFG->dirroot . '/mod/quiz/attemptlib.php');    
        $attemptrec = array();
        $getquiz = $DB->get_record('course_modules', array('id' => $quizid));
        $attempts = quiz_get_user_attempts($getquiz->instance, $userid, 'finished', true);
        $lastfinishedattempt = end($attempts);
        $lastattempt = $lastfinishedattempt->id; 
        $timestart = $lastfinishedattempt->timestart; 
        $timefinish = $lastfinishedattempt->timefinish; 
        //$sumgrades = $lastfinishedattempt->sumgrades; 
        $quiz_grades = $DB->get_records_sql("SELECT gg.rawgrade  FROM {grade_items} gi INNER JOIN {grade_grades} gg ON gi.id=gg.itemid WHERE gi.iteminstance = $getquiz->instance AND gg.userid = $userid "); 
        foreach ($quiz_grades as $quiz_grade) {
            $sumgrades = $quiz_grade->rawgrade;     
        }
        $quiz_attempts = $DB->get_records_sql("SELECT *  FROM {quiz_attempts}   WHERE quiz = $getquiz->instance AND userid = $userid");        
        foreach ($quiz_attempts as $quiz_attempt) {
            $totattempt = $quiz_attempt->attempt;
            $question_attempts = $DB->get_records_sql("SELECT *  FROM {question_attempts} WHERE questionusageid = $quiz_attempt->uniqueid");
            $corr_ans=0;
            $incorr_ans=0;
            foreach ($question_attempts as $question_attempt) {                
                if($question_attempt->rightanswer==$question_attempt->responsesummary){
                    $corr_ans=$corr_ans+1;
                }// check correct answers condtion ends here
                elseif($question_attempt->rightanswer!=$question_attempt->responsesummary){
                    $incorr_ans=$incorr_ans+1;
                }//check incorrect answers condtion ends here
            }
        }          
        $attemptrec = [
            'startedon' => $timestart,
            'completedon' => $timefinish,
            'failedquestions' => $incorr_ans,
            'successfulquestions' => $corr_ans,
            'grade' => $sumgrades,
            'totalattempts' => $totattempt,
        ];
    return $attemptrec;

    }   


    public static function get_dedicationtool_details_parameters() {
        return new external_function_parameters(
           array(
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'candidateid' => new external_value(PARAM_INT, 'candidate id')               
            )
        );                                                          
    }
     public static function get_dedicationtool_details_returns() {
        return new external_single_structure(
            array(
                'coursededication' => new external_value(PARAM_RAW, 'Course dedication'),
                'connectionsperday' => new external_value(PARAM_RAW, 'Connections per day')               
            )
        );
    }
     public static function get_dedicationtool_details($courseid, $candidateid) {         
        global $CFG, $DB, $USER;    
        require_once('/lms/html/blocks/dedication/dedication_lib.php');
   
        $rows = array();

        $mintime = $DB->get_record('course', array('id' => $courseid));
        $maxtime = time();
        $limit = 60 * 60;
        $dm = new block_dedication_manager($courseid, $candidateid, '', $mintime->startdate, $maxtime, $limit);       

        $where = 'courseid = :courseid AND userid = :userid AND timecreated >= :mintime AND timecreated <= :maxtime';
        $params = array(
            'courseid' => $courseid,
            'userid' => $candidateid,
            'mintime' => $mintime->startdate,
            'maxtime' => $maxtime
        );

        $perioddays = ($maxtime - $mintime->startdate) / DAYSECS;
            $daysconnected = array();
            $params['userid'] =  $candidateid;
            
           
            $logs = block_dedication_utils::get_events_select($where, $params);
            
            if ($logs) {
                $previouslog = array_shift($logs);
                $previouslogtime = $previouslog->time;
                $sessionstart = $previouslog->time;
                $dedication = 0;
                $daysconnected[date('Y-m-d', $previouslog->time)] = 1;

                foreach ($logs as $log) {
                    if (($log->time - $previouslogtime) > $limit) {
                        $dedication += $previouslogtime - $sessionstart;
                        $sessionstart = $log->time;
                    }
                    $previouslogtime = $log->time;
                    $daysconnected[date('Y-m-d', $log->time)] = 1;
                }
                $dedication += $previouslogtime - $sessionstart;
            } else {
                $dedication = 0;
            }
        $rows = [
            'coursededication' =>   block_dedication_utils::format_dedication($dedication),
            'connectionsperday' => round(count($daysconnected) / $perioddays, 2)
            ];
        return $rows;
    }
    public static function get_course_progress_parameters() {
        return new external_function_parameters (
            array(
                'courseid' => new external_value(PARAM_INT, 'Course id'),
                'candidateid' => new external_value(PARAM_INT, 'candidate id')
            )
        );
    }
    public static function get_course_progress_returns() {
        return new external_single_structure([
            'candidatename' => new external_value(PARAM_RAW, 'name of candidate'),  
            'progress' => new external_value(PARAM_RAW, 'course progress')
        ]);
    }
    public static function get_course_progress($courseid, $candidateid) {         
        global $CFG, $DB, $USER;  
       
        $context = get_context_instance(CONTEXT_COURSE, $courseid);
      

        require_once "$CFG->dirroot/blocks/iomad_progress/lib.php";
        $studentName = $DB->get_record('user', ['id' => $candidateid]);
        $studentCourse = $DB->get_record('course', ['id' => $courseid]);
        $COURSE = $studentCourse; // set course as global
        $modules = block_iomad_progress_modules_in_use($courseid);
        if (empty($modules)) {
            return [];
        }
        // get block config for course
        $blockrecord = $DB->get_record('block_instances', ['blockname' => 'iomad_progress', 'parentcontextid' => $context->id]);
        if (empty($blockrecord)) {
            return [];
        }
        $blockinstance = block_instance('iomad_progress', $blockrecord);
        $blockconfig = $blockinstance->config;
        $events = block_iomad_progress_event_information($blockconfig, $modules, $studentCourse->id);

        // find user events as per the new iomad progress block
        $userevents = block_iomad_progress_filter_visibility($events, $candidateid, $context, $studentCourse);

        $attempts = block_iomad_progress_attempts($modules, $blockconfig, $events, $candidateid, null);
        // get progress
        // $progress = block_iomad_progress_percentage($events, $attempts);
        // calculating the percventage as per the new iomad progress block
        $progress = block_iomad_progress_percentage($userevents, $attempts, true);

        return ['candidatename' => $studentName->firstname.' '.$studentName->lastname, 'progress' => $progress];

    }

    public static function createconversation_parameters() {
        return new external_function_parameters (
            array(
                'courseid' => new external_value(PARAM_INT, 'Course id'),
                'dialogueid' => new external_value(PARAM_INT, 'Dialogue id'),
                'userid' => new external_value(PARAM_INT, 'User id'),
                'candidatesid' =>new external_value(PARAM_RAW, 'candidatesid'),
                'content' => new external_value(PARAM_RAW, 'content')
            )
        );
    }
    public static function createconversation_returns() {
        return new external_single_structure(
                array(
                 'conversationid' => new external_value(PARAM_INT, 'conversation id'),                 
                 'response' =>  new external_value(PARAM_RAW, 'response')                     
                )           
        );
    }
   public static function createconversation($courseid, $dialogueid, $userid, $candidatesid, $content) {         
        global $CFG, $DB, $USER;   
   
        $cm = $DB->get_record('course_modules', array('id'=>$dialogueid));        
        // Conversation record.
        $record = new \stdClass();        
        $record->course = $courseid;
        $record->dialogueid = $cm->instance;
        $record->subject = $content; 
        $conversationid = $DB->insert_record('dialogue_conversations', $record);     
        
        $recordpart = new \stdClass();
        $recordpart->dialogueid = $cm->instance;
        $recordpart->conversationid = $conversationid;
        $recordpart->userid = $userid;
        $DB->insert_record('dialogue_participants', $recordpart);

        $candidatesarray = explode(',', $candidatesid);
        if($candidatesarray){
            foreach ($candidatesarray as $candidate) {
                $recordpart = new \stdClass();
                $recordpart->dialogueid = $cm->instance;
                $recordpart->conversationid = $conversationid;
                $recordpart->userid = $candidate;
                $DB->insert_record('dialogue_participants', $recordpart);
            }
        }
        
        /*$record = new \stdClass();
        $record->dialogueid = $dialogueid;
        $record->conversationid = $conversationid;
        $record->type = null;
        $record->sourceid = null;
        $record->includefuturemembers = false;
        $record->cutoffdate = 0;
        $DB->insert_record('dialogue_bulk_opener_rules', $record);*/

        $return = array('conversationid' => $conversationid, 'response' => 'OK');
        return $return;  
        //return json_encode($return, JSON_UNESCAPED_SLASHES);
    }

    public static function postdialogue_parameters() {
        return new external_function_parameters (
            array(
                'conversationid' => new external_value(PARAM_INT, 'Course id'),               
                'userid' => new external_value(PARAM_INT, 'User id'),
                'content' => new external_value(PARAM_RAW, 'content')
            )
        );
    }
    public static function postdialogue_returns() {
        return new external_single_structure(
            array(
                'postid' => new external_value(PARAM_INT, 'conversation id'),                 
                'response' =>  new external_value(PARAM_RAW, 'response')
            )           
        );
    }
   public static function postdialogue($conversationid, $userid, $content) {         
        global $CFG, $DB, $USER;   
   
        // Conversation record.
        $conversation = $DB->get_record('dialogue_conversations', array('id'=>$conversationid));
        $record = new \stdClass();
        $record->dialogueid = $conversation->dialogueid;
        $record->conversationid = $conversationid;
        $record->conversationindex = 1;
        $record->authorid = $userid;
        $record->body = $content;
        $record->bodyformat = 1;
        $record->bodytrust = 0;
        $record->attachments = 0;
        $record->state = 'open';
        $record->timecreated = time();
        $record->timemodified = time(); 
        $postid = $DB->insert_record('dialogue_messages', $record);             
       
        $return = array('postid' => $postid, 'response' => 'OK');
        return $return;  

    }    
}