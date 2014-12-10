<?php
  /*
     System Exclusive
   */

// require_once 'IO/Bit.php';
require_once('IO/MIDI/SysEx/NSX39.php');

class IO_MIDI_SysEx {
    function dumpPayload($payload, $opts = array()) {
        $sysExPrefix = IO_MIDI_SysEx_NSX39::SYSEX_PREFIX;
        $sysExPrefixLen = strlen($sysExPrefix);
    	if (strncmp($payload, $sysExPrefix, $sysExPrefixLen) === 0) {
            $nsx39 = new IO_MIDI_SysEx_NSX39();
            $nsx39->dumpPayload(substr($payload, $sysExPrefixLen));
        }
    }
}
