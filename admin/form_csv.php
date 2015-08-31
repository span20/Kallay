<?php
ini_set('include_path', '../libs/pear'.PATH_SEPARATOR.
						'../'.PATH_SEPARATOR.
						ini_get('include_path'));
include_once 'includes/config.php';

$form_id = intval($_REQUEST['form_id']);
$header = "";
$data = "";
$answers = array();

$select = "SELECT name, email, answer_id FROM iShark_FormBuilder_Users WHERE form_id = '$form_id' order by answer_id asc";
$export = $mdb2->query($select);

while( $row = $export->fetchRow() )
{
	$answers[$row['answer_id']]['email'] = $row['email'];
	$answers[$row['answer_id']]['user'] = $row['name'];
	
	$query_fields = "
		SELECT f.field_name, f.field_id 
		FROM iShark_FormBuilder_Answers AS a
		LEFT JOIN iShark_FormBuilder_Fields AS f ON f.field_id = a.field_id
		WHERE a.form_id = '$form_id' AND a.answers_id = '".$row['answer_id']."'
		GROUP BY f.field_id
		ORDER BY f.field_name ASC
	";
	$result_fields = $mdb2->query($query_fields);
	while( $row_fields = $result_fields->fetchRow() ) {
		
		$query_answers = "
			SELECT 
			CASE WHEN answer != ''
			THEN a.answer
			ELSE v.value
			END AS answer
			FROM iShark_FormBuilder_Answers AS a
			LEFT JOIN iShark_FormBuilder_Fields AS f ON f.field_id = a.field_id
			LEFT JOIN iShark_FormBuilder_Values AS v ON v.values_id = a.value_id
			WHERE a.form_id = '$form_id' AND a.field_id = '".$row_fields['field_id']."' AND a.answers_id = '".$row['answer_id']."'
			ORDER BY a.answers_id ASC
		";
		$answer_values = "";
		$result_answers = $mdb2->query($query_answers);
		if ($result_answers->numRows() > 0 ){
			while( $row_answers = $result_answers->fetchRow() ) {
				$answer_values .= $row_answers['answer'].", ";
			}
		}
		$answers[$row['answer_id']][$row_fields['field_name']] = $answer_values; 
	}
}

$line = '';
foreach ( $answers as $key => $value ) {
	$header = "";
    foreach( $value as $key2 => $answer )
    {                   
		$header .= '"' . $key2 . '"' . ";";
        if ( ( !isset( $answer ) ) || ( $answer == "" ) )
        {
            $answer = ";";
        }
        else
        {
            $answer = str_replace( '"' , '""' , $answer );
            $answer = '"' . $answer . '"' . ";";
        }
        $line .= $answer;
		
    }
	$line .= "\n";
}

$data .= trim( $header ) . "\n";
$data .= trim( $line ) . "\n";
$data = str_replace( "\r" , "" , $data );

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=urlap".date("Ymd").".csv");
header("Pragma: no-cache");
header("Expires: 0");
print "$data";

?>
