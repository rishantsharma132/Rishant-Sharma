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


namespace local_quizapis;
defined('CREOLMS_INTERNAL') || die();
require_once($CFG->dirroot.'/config.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
class observers{
    public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
	    global $DB, $CFG, $PAGE, $USER;
	    $eventdata = $event->get_data();
	    $cmid = $eventdata['contextinstanceid'];
	   
	     $resultsend = $DB->get_record_sql("SELECT id FROM {local_metadata} WHERE instanceid = $cmid AND fieldid = '1' AND data = '1'");
	    
	    if ($resultsend) {	
		    $userobj = $DB->get_record('user', array('id' => $eventdata['userid']));
		    $attempt = $DB->get_record('quiz_attempts', array('id' => $eventdata['objectid'], 'userid' => $eventdata['userid']));
		    $quiz = $DB->get_record('quiz', array('id' => $eventdata['other']['quizid']));
		    $attempt = $DB->get_record('quiz_attempts', array('id' => $eventdata['objectid']));	   
		    $attemptobj = quiz_create_attempt_handling_errors($eventdata['objectid'], $eventdata['contextinstanceid']);	   
		    $returnarray = array();		 
		    $returnarray['userLogin'] = $userobj->firstname.' '.$userobj->lastname;
		    $returnarray['userEmail'] = $userobj->email;
		    $returnarray['eventCode'] = $eventdata['courseid'];
		    $returnarray['quizCode'] = $eventdata['other']['quizid'];
		    $returnarray['quizState'] = 'finished';
		    $returnarray['timestamp'] = time();
		    $returnarray['startedOn'] = $attempt->timestart;
		    $returnarray['completedOn'] = $attempt->timefinish;
		    $returnarray['Marks'] = $attempt->sumgrades.'/'.$quiz->sumgrades;
		    $returnarray['Feedback'] = 'You did not answer correctly the minimum number of questions required to pass the exam. Your level of preparation is still not enough. We recommend that you repeat the course, in particular the specific topics on which you have committed more errors.';	 	   
	           
		     
		    $data = json_encode($returnarray);
		  
		$url = "http://192.168.100.33:8010/EEM/s/rest/channel/notify/LMS-OMT";

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$headers = array(
			   "Accept-Language: en,uk-UA;q=0.9,uk;q=0.8,en-US;q=0.7,ru;q=0.6",
			   "Authorization: Basic YWRtaW46JUhkbWFkYSoxMjAmKm1OZA==",
			   "Cache-Control: no-cache",
			   "Connection: keep-alive",
			   "Pragma: no-cache",
			   "Referer: http://192.168.100.33:8010/EEM/pages/main.seam?cid__=274",
			   "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36",
			   "accept: application/json, */*; q=0.01",
			   "content-Type: application/json",
			);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		/*	$data = '{"useremail":"o@o.com","userLogin":"login","eventCode":"555","quizCode":"123","quizState":"Finished","startedOn":"2022-05-31T13:55:36.999Z","completedOn":"2022-05-31T15:55:36.999Z","Marks":"12/60","feedback":"You did not answer correctly the minimum number of questions required to pass the exam. Your level of preparation is still not enough. We recommend that you repeat the course, in particular the specific topics on which you have committed more errors."}';*/

			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

			//for debug only!
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			/*print_r($data);*/

			$resp = curl_exec($curl);
			curl_close($curl);
			/*echo "--------------------";
			print_r($resp);*/
		      
		   /* exit();*/
		}			
    }  
}