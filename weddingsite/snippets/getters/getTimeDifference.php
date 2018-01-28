<?php
/**
 * Wedding site getTimeDifference
 *
 * Copyright 2011 by S. Hamblett <steve.hamblett@linux.com>
 *
 * Site time difference snippet
 * 
 * Parameters :-
 *
 * start - start time unixtimestamp
 * end - end time unix timestamp
 * 
 * Returns :-
 * 
 * A JSON encoded array of the form 
 * $diff['days']    = int
 * $diff['hours']   = int
 * $diff['minutes'] = int
 * $diff['seconds'] = int
 * 
 */
$uts['start'] = $start;
$uts['end'] = $end;
if( $uts['start']!==-1 && $uts['end']!==-1 )
{
    if( $uts['end'] >= $uts['start'] )
    {
        $diff = $uts['end'] - $uts['start'];
        if( $days=intval((floor($diff/86400))) )
            $diff = $diff % 86400;
        if( $hours=intval((floor($diff/3600))) )
            $diff = $diff % 3600;
        if( $minutes=intval((floor($diff/60))) )
            $diff = $diff % 60;
        $diff = intval( $diff );            
        $diffArray = array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff);
        $diffArrayString = json_encode($diffArray);
        return $diffArrayString;
    }
}
