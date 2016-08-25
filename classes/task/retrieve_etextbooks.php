<?php
namespace block_etextbook\task;
global $CFG;

class retrieve_etextbooks extends \core\task\scheduled_task
{
    public function get_name()
    {
        return get_string('retrieve_etextbooks', 'block_etextbook');
    }

    public function execute()
    {
        global $CFG, $DB, $COURSE;
        $librarylink = get_config('etextbook', 'Library_link');
        $etxtblktbl = 'block_etextbook';
        $DB->execute("TRUNCATE TABLE {block_etextbook}");
        $books = simplexml_load_file($librarylink);
        $tbook = new \stdClass();

        // For loop to get all course numbers with books
        foreach ($books as $book) {
            $tbook->book_url = (string)$book->field_ebook_url;
            $tbook->img_url = (string)$book->field_ebook_image;
            $tbook->title = (string)$book->field_ebook_title;
            $tbook->dept = (string)$book->field_ebook_subject;
            $tbook->course_title = (string)$book->field_course_title;
            $tbook->course_number = (string)$book->field_course_number;
            $tbook->section = (string)$book->field_ebook_section;
            $tbook->instructor = (string)$book->Instructor;
            $tbook->term = (string)$book->Term;
            $termswitcharoo = explode(" ", $tbook->term);
            $tbook->term = $termswitcharoo[1] . " " . $termswitcharoo[0];

            if(strlen($tbook->section) > 1){
                echo "\n\n\t ))))))))))))))))))) MORE THAN ONE SECTION SO EXPLODE RUN FOREACH INSIDE IF STATEMENT";
                $sections = explode(',', ($tbook->section));
                echo "\n multiple sections -------- \n ";
                foreach($sections as $section){
                    $tbook->section = $section;
                    $this->merge_courses_with_books($tbook);
                }
            }
            else{
                $this->merge_courses_with_books($tbook);
            }
        }
    }
    public function merge_courses_with_books($tbook){
        global $DB;
        $tbook->courseid = "";
        $coursenameregexp = $tbook->term . ' ' . $tbook->dept . ' ' . $tbook->course_number . ' ' . str_pad($tbook->section, 3, "0", STR_PAD_LEFT);
        echo "\n\n\t >>>>> Course found that has textbook ---> " . $coursenameregexp;

        $sqlt = "SELECT DISTINCT(c.id)
                     FROM mdl_enrol_ues_semesters sem
                     INNER JOIN mdl_enrol_ues_sections sec ON sec.semesterid = sem.id
                     INNER JOIN mdl_enrol_ues_courses cou ON cou.id = sec.courseid
                     INNER JOIN mdl_course c ON c.idnumber = sec.idnumber
                     WHERE sec.idnumber IS NOT NULL
                     AND c.idnumber IS NOT NULL
                     AND sec.idnumber <> ''
                     AND c.idnumber <> ''
                     AND CONCAT(sem.year, ' ', sem.name, ' ', cou.department, ' ', cou.cou_number, ' ', sec.sec_number) = :coursename";

        $records = $DB->get_record_sql($sqlt, array('coursename' => $coursenameregexp));
        if($records->id){
            $tbook->courseid = $records->id;
            $DB->insert_record('block_etextbook', $tbook);
        }
        else{
            echo "\n\n [][][][][][][][[[[[[[ Book found but no course id \n\n";
        }
    }

}

