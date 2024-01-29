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
* @package local_quizsubmission_event
* @category local
* @copyright  ELS <admin@elearningstack.com>
* @author eLearningstack
*/

defined('CREOLMS_INTERNAL') || die();
 
$observers  = array( 
    array(
        'eventname' => '\mod_quiz\event\attempt_submitted',
        'callback' => '\local_quizapis\observers::attempt_submitted',
        'internal'    => true,
        'priority'    => 9999,
    )

);

