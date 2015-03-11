<?php

require_once 'IO/MIDI.php';

$options = getopt("f:v:c:");

$program_number = 0;
if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    echo "Usage: php midiprogram.php -f <midi_file> -p <program_number>\n";
    echo "ex) php midiprogram.php -f in.mid -p 0\n";
    exit(1);
}
$velocity_number = isset( $options['v'] )  ? (int)$options['v'] : 0 ;
$channel_number = isset( $options['c'] )  ? (int)$options['c'] : 0 ;
$mididata = file_get_contents($options['f']);

$midi = new IO_MIDI();
$midi->parse($mididata);

$res = [];

foreach ($midi->tracks as $key => &$value) {
	foreach ($value["track"] as $key2 => &$value2) {
		if(isset($value2["Velocity"]) && ( $value2["EventType"] === 9 )  && ( $value2["Velocity"] > 0 )  ){
			if($value2["MIDIChannel"] === $channel_number){
					$value2["Velocity"] = $velocity_number;
			}
		}
	}
}

unset($value);
unset($value2);

$opts = array();

//$opts['runningstatus'] = true;

echo $midi->build($opts);
