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

$functions = array (
  'local_quizapis_details' => array (
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'get_quiz_questions',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Returns quiz and questions detail.',
        'type'          => 'read',
    ), 
    'local_quizquesapis_details' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'get_questions_details',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Returns questions detail.',
        'type'          => 'read',
    ), 
    'local_quescatapis_details' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'get_questions_category',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Returns child category of parent questions category .',
        'type'          => 'read',
    ), 
    'local_update_quizattempts' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'update_quizattempts',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Update quiz attempts.',
        'type'          => 'read',
    ), 
    'local_get_quizattempts' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'get_quizattempts',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Get quiz attempts.',
        'type'          => 'read',
    ), 
    'local_dedicationtool_details' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'get_dedicationtool_details',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Dedication tool details.',
        'type'          => 'read',
    ), 
    'local_course_progress' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'get_course_progress',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Get Course Progress details.',
        'type'          => 'read',
    ),
    'local_createconversation' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'createconversation',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Get dialogue data details.',
        'type'          => 'read',
    ),
    'local_postconversation' => array(
        'classname'     => 'local_quizapis_external',
        'methodname'    => 'postdialogue',
        'classpath'   => 'local/quizapis/externallib.php',
        'description'   => 'Get dialogue data details.',
        'type'          => 'read',
    ),
);

$services = array (
      'local_service' => array (
          'functions' => array ('local_quizapis_details','local_quizquesapis_details','local_quescatapis_details','local_update_quizattempts', 'local_get_quizattempts', 'local_dedicationtool_details', 'local_course_progress','local_createconversation','local_postconversation'),
          'restrictedusers' => 0,
          'enabled' => 1,
       )
  );