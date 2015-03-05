<?php

require_once 'IO/MIDI.php';

$options = getopt("f:c:C:r");

function usage() {
    fprintf(STDERR, "Usage: php midigrep.php -f <midi_file> [-r] {-c <channels> | -C <channels>}\n");
    fprintf(STDERR, "ex) php midigrep.php -f in.mid -c 1\n");
    fprintf(STDERR, "ex) php midigrep.php -f in.mid -C 15,16\n");
}

if ((isset($options['f']) === false) || (is_readable($options['f']) === false) || (isset($options['c']) xor !isset($options['C']))) {
    usage();
    exit(1);
}

$mididata = file_get_contents($options['f']);

if (isset($options['c'])) {
    $channels = explode(',', $options['c']);
    $channels = array_map("intval", $channels);
} else {
    $channels = explode(',', $options['C']);
    $channels = array_map("intval", $channels);
    $channels = array_diff(range(1, 16), $channels);
}

$midi = new IO_MIDI();
$midi->parse($mididata);

foreach ($midi->tracks as $idx => $track) {
    $deltaTimeCarry = 0;
    foreach ($track['track'] as $idx2 => $chunk) {
        $deleted = false;
        if (isset($chunk['MIDIChannel'])) {
	    if (in_array($chunk['MIDIChannel'], $channels) === false) {
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
