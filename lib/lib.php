<?php
/*
 *
*/
include 'xml_regex.php';

function get_events() {
    $type = get_option('wtit_type');
    $xml = '';
    if ($type=="next") {
        $xml = get_xml("next");
    } else {
        $xml = get_xml("today");
    }
    $list = get_list($xml);
    return $list;
}

/*
 * This function should be used to get the xml file,
*/
function get_xml($one="today") {
    $tags = get_option('wtit_tags');
    $api_key = get_option('wtit_api_key');
    $api_url = get_option('wtit_api_url');

    // Getting the xml
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,
            "$api_url/$one/$tags/$api_key");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xml = curl_exec($ch);
    curl_close($ch);
    return $xml;
}

function get_list($xml) {
    // Create an array of item elements from the XML feed.
    $items = element_set('question', $xml);
    $list = null;

    // Adding the questions into the array
    $num = get_option('wtit_num');
    $i = 1;
    if ($items!=null) {
        foreach ($items as $item) {
            $q_text = value_in('question_text', $item);
            $answer = date_format(new DateTime(value_in('answer', $item), new DateTimeZone('GMT')), "F d, Y H:i");
            $link = value_in('link', $item);
            $list[] = array(
                    "q_text"    =>  $q_text,
                    "answer"    =>  $answer,
                    "link"      =>  $link
            );
            $i++;
            if ($i>$num)
                break;
        }
    }
    return $list;
}
?>