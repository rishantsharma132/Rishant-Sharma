<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
* @package local_sendnotification
* @category local
* @copyright  Pal infocom 
* @author Deepak Sharma
*/
namespace local_sendnotification\task;
/**
 * An example of a scheduled task.
 */
class sendnotification_task extends \core\task\scheduled_task {
    public function get_name(){
        return get_string('pluginname','local_sendnotification');
    }

    /**
     * Execute the task.
     */

    // public function execute(){
    //     global $CFG, $DB,$USER;
    //     require_once($CFG->dirroot .'/local/sendnotification/lib.php');
    //     send_mail();//function to execute
    // }   
    
    /**
     * Execute the task.
     */

     public function execute(){
        global $CFG, $DB,$USER;
        require_once($CFG->dirroot .'/local/sendnotification/lib.php');
        send_certificate_email();//function to execute
    } 
    
    /**
     * Execute the task.
     */

     public function execute(){
        global $CFG, $DB,$USER;
        require_once($CFG->dirroot .'/local/sendnotification/lib.php');
        send_status_email_to_users();//function to execute
    } 
    
    /**
     * Execute the task.
     */

     public function execute(){
        global $CFG, $DB,$USER;
        require_once($CFG->dirroot .'/local/sendnotification/lib.php');
        send_status_email_to_admin();//function to execute
    } 
}