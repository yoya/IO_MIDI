<?php
  /*
     GM (General MIDI)
     https://ja.wikipedia.org/wiki/General_MIDI
   */


class IO_MIDI_GM {
    static $programNameTable = array(
     1 => 'Acoustic Piano',
     2 => 'Bright Piano'
   );
   static function getProgramName($program) {
       if (isset(self::$programNameTable[$program])) {
           return self::$programNameTable[$program];
       }
       return "Unknown";
   }
}
