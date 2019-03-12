<?php
$collection = array(1,2,4,7,14,15,16);
$missing = check_array($collection);
echo $missing;

/**
 * check_array( $collection array)
 * Receives an array of integers ranging from 0 to 20 and return a string of missing numbers.
 *
 * Assuming 0 -20
 * Assuming not to include collection values to result
 *
 * @param string $collection of values in a string between 0 - 20
 * @return String values of missing numbers
 */

function check_array($collection)
{
    $return = '';
    $previousResult = '';
    $rangeArr = range(0,20);
    $results = array_diff($rangeArr,$collection);
    foreach ($results as $key=>$result) {
      if($result == ($previousResult + 1 )){
        if (substr($return, -1) != "-")
          $return = $return ."-";

      }else{
          if (substr($return, -1) != $previousResult)
            $return = $return.$previousResult.','.$result;
      }
      $previousResult = $result;
    }
    return $return.$previousResult;
}
?>
