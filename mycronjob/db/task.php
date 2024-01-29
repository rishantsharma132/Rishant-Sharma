<?php
/**
 * @package local_my_cronjob
 * @category local
 * @copyright  Pal infocom 
 * @author Kulwinder Singh
 */

defined('MOODLE_INTERNAL') || die();
$tasks = [
    [
        'classname' => 'local_mycronjob\task\mycronjob_task',
        'blocking' => 0,
        'minute' => '0',
        'hours' => '0',
        'day' => '1',
        'month' => '0',
        'dayofweek' => '0',
    ]
];
?>