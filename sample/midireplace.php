<?php

require_once 'IO/MIDI.php';

$options = getopt("f:c:r");

function usage() {
    fprintf(STDERR, "Usage: php midireplace.php -f <midi_file> [-r] -c <channels> \n");
    fprintf(STDERR, "ex) php midireplace.php -f in.mid -c 1=16\n");
}

if ((isset($options['f']) === false) || (is_readable($options['f']) === false) || (isset($options['c']) === false)) {
    usage();
    exit(1);
}

$mididata = file_get_contents($options['f']);

$channel_transtable = array();
if (isset($options['c'])) {
    $channel_pairs = explode(',', $options['c']);
    foreach ($channel_pairs as $pair) {
        if (preg_match('/(\d+)=(\d+)/', $pair, $m)) {
	      $channel_transtable[intval($m[1])] = intval($m[2]);
	} else {
	    usage();
	    exit(1);	   
	}
    }
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
	    $channel = $chunk['MIDIChannel'];
	    if (isset($channel_transtable[$channel])) {
	        if ($chunk['EventType'] < 0xF) {
		    $midi->tracks[$idx]['track'][$idx2]['MIDIChannel'] = $channel_transtable[$channel];
                }
            }
        }
    }
}

$opts = array();

if (isset($options['r'])) {
    $opts['runningstatus'] = true;
}

echo $midi->build($opts);
