<?php

require_once('../../config.php');
require_login();
global $OUTPUT, $PAGE, $DB, $CFG;
$id = optional_param('id', 0, PARM_TEXT);
$PAGE->set_url('/local/cronjob/send_mail.php');
$PAGE->set_heading(get_string('pluginname', 'local_my_cronjob'));
$PAGE->set_title(get_string('pluginname', 'local_my_cronjob'));
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add(get_string('pluginname', 'local_my_cronjob'));
echo $OUTPUT->header();
// $render = $PAGE->get_render('local_my_cronjob');
// echo $render->send_mail_report();
echo $OUTPUT->footer();
