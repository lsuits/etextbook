<?php

/**
 * Language Details: First Version
 * Language Strings for etextbook
 *
 * @package    block_etextbook
 * @copyright  2016 Lousiana State University - David Elliott, Robert Russo, Chad Mazilly
 * @author     David Elliott <delliott@lsu.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @version 1.0
 */

$string['pluginname'] = 'LSU E-Textbooks block';
$string['etextbook'] = 'E-Textbooks';
$string['etextbook:addinstance'] = 'Add a new E-Textbook block';
$string['etextbook:myaddinstance'] = 'Add a new E-Textbook block to the My Moodle Page';

$string['linktolsulibraries'] = '<p><br /><a href = "http://www.lib.lsu.edu/ebooks"> Free access through LSU Libraries! </a></p><hr>';
$string['startlinktag'] = '<a href="';
$string['field_ebook_url'] = '{$a}'; 
$string['endlinktag'] = '">';

$string['misx'] = '"' .'><h3>{$a->field_ebook_title}</h3></a>'
    . '<img width = "50%" class = "etextimg img-rounded img-responsive" src = " ' . '{$a->field_ebook_image}' . ' "> ';
    //.'<br /> Author: {$a->author}';
    // @todo add author information

//$string['endlinktag'] =  