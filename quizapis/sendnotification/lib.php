<?php
global $DB, $USER, $CFG;
defined('MOODLE_INTERNAL') || die;
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/completionlib.php');

class send_mail {
    public static function send_certificate_email($courseid, $userid) {
        global $DB, $USER;

        $user = $DB->get_record('user', array('id' => $userid));
        $course = $DB->get_record('course', array('id' => $courseid));

        // Check if the user has completed the course
        $cinfo = new completion_info($course);
        $is_complete = $cinfo->is_course_complete($userid);

        if ($is_complete) {
            // Send certificate email to user
            $subject = get_string('send_certificate_subject', 'local_sendnotification');
            $message = get_string('send_certificate_message', 'local_sendnotification', $course->fullname);
            $messagehtml = html_to_text($message);

            // Get the sender user record (you can adjust this based on your needs)
            $fromuser = $DB->get_record('user', array('id' => $USER->id));

            // Send the email to the user
            $success = email_to_user($user, $fromuser, $subject, $message, $messagehtml);

            if ($success) {
                echo "Certificate email sent to user successfully.";
            } else {
                echo "Failed to send certificate email to user.";
            }
        } else {
            echo "User has not completed the course.";
        }
    }

    public static function send_status_email_to_users() {
        global $DB, $USER;

        // Query users who have not started or completed the course
        $users = self::get_users_with_incomplete_course(); // Implement this function

        foreach ($users as $user) {
            // Compose and send status emails to users
            $subject = 'Course Progress Status';
            $message = "Hello {$user->firstname}, this is a reminder about your course progress.";
            $messagehtml = html_to_text($message);

            // Send email to the user
            $success = email_to_user($user, $USER, $subject, $message, $messagehtml);

            if ($success) {
                echo "Status email sent to user {$user->fullname} ({$user->email})<br>";
            } else {
                echo "Failed to send status email to user {$user->fullname} ({$user->email})<br>";
            }
        }
    }

    public static function send_status_email_to_admin() {
        global $DB, $USER;

        // Query course progress for users and prepare a summary
        $progress_summary = self::generate_course_progress_summary(); // Implement this function

        // Send status email to admin with the summary
        $admin = $DB->get_record('user', array('id' => 2)); // Replace with actual admin user ID
        $admin_subject = 'Weekly Course Progress Summary';
        $admin_message = "Here is the weekly course progress summary:\n\n{$progress_summary}";
        $admin_messagehtml = html_to_text($admin_message);

        $admin_success = email_to_user($admin, $USER, $admin_subject, $admin_message, $admin_messagehtml);

        if ($admin_success) {
            echo "Status summary email sent to admin successfully.";
        } else {
            echo "Failed to send status summary email to admin.";
        }
    }
}
