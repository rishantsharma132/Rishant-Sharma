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
 * This block allows the user to give the course a rating, which
 * is displayed in a custom table (<prefix>_block_rate_course).
 *
 * @package    local_sendnotification
 * @subpackage reportes
 * @copyright   Pal infocom 
 * @license    
 */
require_once('../../config.php');
require_login();
global $OUTPUT,$PAGE,$DB,$CFG;
$id = optional_param('id',0,PARM_TEXT);
$PAGE->set_url('/local/cronjob/send_mail.php');
$PAGE->set_heading(get_string('pluginname' , 'local_sendnotification'));
$PAGE->set_title(get_string('pluginname' , 'local_sendnotification'));
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add(get_string('pluginname' ,'local_sendnotification'));
echo $OUTPUT->header();
echo $OUTPUT->footer();
?>