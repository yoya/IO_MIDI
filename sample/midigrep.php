<?php

require_once 'IO/MIDI.php';

$options = getopt("f:c:r");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false) || (isset($options['c']) === false)) {
    fprintf(STDERR, "Usage: php midigrep.php -f <midi_file> [-r] -c <channel>\n");
    fprintf(STDERR, "ex) php midigrep.php -f in.mid -c 0\n");
    exit(1);
}

$mididata = file_get_contents($options['f']);
$channel = $options['c'] + 0;

$midi = new IO_MIDI();
$midi->parse($mididata);

foreach ($midi->tracks as $idx => $track) {
    $deltaTimeCarry = 0;
    foreach ($track['track'] as $idx2 => $chunk) {
        $deleted = false;
        if (isset($chunk['MIDIChannel'])) {
	    if ($chunk['MIDIChannel'] != $channel) {
	        if ($chunk['EventType'] < 0xF) {
		    $deltaTimeCarry += $chunk['DeltaTime'];
                    unset($midi->tracks[$idx]['track'][$idx2]);
		    $deleted = true;
                }
            }
        }
        if ($deleted === false) {
            if ($deltaTimeCarry > 0) {
	        $midi->tracks[$idx]['track'][$idx2]['DeltaTime'] += $deltaTimeCarry;
                $deltaTimeCarry = 0;
           }
       }

    }
}

// $midi->tracks[$idx]['track'] = array_values($midi->tracks[$idx]['track']);


$opts = array();


if (isset($options['r'])) {
    $opts['runningstatus'] = true;
}

echo $midi->build($opts);
