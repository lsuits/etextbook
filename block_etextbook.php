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
 * Block displaying information about whether or not there is an etextbook
 * for the course
 *
 * @package    block_etextbook
 * @copyright  2016 Lousiana State University - David Elliott, Robert Russo, Chad Mazilly
 * @author     David Elliott <delliott@lsu.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_etextbook extends block_base {

    public function init() {
        $this->title = get_string('etextbook', 'block_etextbook');
    }

    public function get_content() {
        GLOBAL $COURSE, $CFG, $DB;
        // Get content from library.
        $books = simplexml_load_file('http://www.lib.lsu.edu/ebooks/xml');
        $sql = "SELECT uc.department, uc.cou_number, us.sec_number
                FROM mdl_course c
                INNER JOIN mdl_enrol_ues_sections us ON us.idnumber = c.idnumber
                INNER JOIN mdl_enrol_ues_courses uc ON us.courseid = uc.id
                WHERE c.id = :courseid";
        $records = $DB->get_records_sql($sql, array('courseid' => $COURSE->id));
        $coursenumber = $records['MATH']->cou_number;
        $sectionnumber = $records['MATH']->sec_number;
        var_dump($sectionnumber);
        $foundabook = false;
        foreach ($books as $node) {
            preg_match("/Section (.*)\</", $node->field_course_number, $matches);
            $sections = explode(',', $matches[1]);
            echo '<h1> SECTIONS BEFORE str_pad</h1>';
            var_dump($sections);
            
            foreach($sections as $section){
                echo '<h1> SECTION in the SECTIONS array </h1>';
                $section = str_pad($section, 3, '0', STR_PAD_LEFT);
                var_dump($section);
            }
            
            if ( $coursenumber == substr((string) $node->field_course_number, 0, 4) && in_array($sectionnumber, $sections) ) {
                $astring = get_string('headline', 'block_etextbook') . $node->field_ebook_title;
                $bookcover = '<img width = "50%" src = " ' . $node->field_ebook_image . ' "> ';
                $foundabook = true;
            }
        }
        if ( $foundabook ) {
            $this->content = new stdClass;
            $this->content->text = $astring;
            $this->content->footer = $bookcover;
        }
        return $this->content;
    }

}
