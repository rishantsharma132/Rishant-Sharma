<?php
/**
 * @package local_my_cronjob
 * @category local
 * @copyright  Pal infocom 
 * @author Kulwinder Singh
 */

namespace local_mycronjob\task;

class mycronjob_task extends \core\task\scheduled_task
{
    public function get_name()
    {
        return get_string('pluginname', 'local_mycronjob');
    }
    public function execute()
    {
        global $CFG, $DB, $USER;
        require_once($CFG->dirroot . '/local/mycronjob/lib.php');
        send_mail();
    }
}
