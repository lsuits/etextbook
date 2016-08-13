<?php
namespace block_etextbook\task;
global $CFG;
require_once($CFG->dirroot . '/blocks/etextbook/lib.php');

class retrieve_etextbooks extends \core\task\scheduled_task {      
    public function get_name() { return get_string('retrieve_etextbooks', 'block_etextbook'); }
                                                                     
    public function execute() {
        global $CFG, $DB, $COURSE;
        $librarylink = get_config('etextbook', 'Library_link');
        $etxtblktbl = 'block_etextbook';
        $books = simplexml_load_file($librarylink);
	    $tbook = new \stdClass();

        // For loop to get all course numbers with books
        foreach ($books as $book){
            $tbook->book_url	    = (string)$book->field_ebook_url;
            $tbook->img_url		    = (string)$book->field_ebook_image;
            $tbook->title		    = (string)$book->field_ebook_title;
            $tbook->dept		    = (string)$book->field_ebook_subject;
            $tbook->course_title	= (string)$book->field_course_title;
            $tbook->course_number	= (string)$book->field_course_number;
            $tbook->section		    = (string)$book->field_ebook_section;
            $tbook->instructor	    = (string)$book->Instructor;
            $tbook->term		    = (string)$book->Term;
            $termswitcharoo         = explode(" ", $tbook->term);
            $tbook->term            = $termswitcharoo[1] . " " . $termswitcharoo[0];

            //find the moodle courseid in the DB
            $moodlecourses = "course";
            $coursenameregexp = $tbook->term . ' ' . $tbook->dept . ' ' . $tbook->course_number . " for +.* " . $tbook->instructor;
            echo 'COURSE LOOKUP REGEXP = '.$coursenameregexp;
            $sql = "SELECT id
                    FROM {course}
                    WHERE fullname REGEXP :coursename";
            if($DB->record_exists_sql($sql, array('coursename'=>$coursenameregexp))){
                $records = $DB->get_record_sql($sql, array('coursename' => $coursenameregexp));
                echo "------------";
                echo " records \n";
                $tbook->courseid = $records->id;
                echo 'her';
                if(!$DB->record_exists_select($etxtblktbl, array('book_url' => $tbook->book_url))){ //, 'img_url' => $tbook->img_url, 'title'=>$tbook->title, 'dept' => $tbook->dept, 'course_title' => $tbook->course_title, 'course_numner' => $tbook->course_number, 'section' => $tbook->section, 'instructor'=>$tbook->instructor, 'term' => $tbook->term))){
                    $DB->insert_record($etxtblktbl, $tbook);

                }


            }
            else{
                echo '\nTHERE IS A BOOK BUT NO COURSE\n';
            }
        }
    }                                                                                                                               
} 
