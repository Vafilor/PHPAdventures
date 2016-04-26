<?php
//Travel.php 
//Andrey Melnikov
//4.25.2016
//Provides functions to go through every possible combination of values in an array of arrays and then do something with them 
//via a passed in function.

//TODO(s): 
//Clean up code
//Documentation
// *Type hints
// *Update format to PHPDoc

//$array is expected to be an array of arrays.
//$callback is expected to be a function name that takes an array as an argument.
function traverse($array, callable $callback)
{
    //Index is essentially a list of iterators. The keys to index are 0,1,2,....count($array)-1
    $index = [];
    
    foreach($array as $value) {
        $index[] = $value;
    }

    $maxIndex = count($index) - 1;
    $currentIndex = $maxIndex;

    while( current($index[0]) !== false ) {
        
        while( !current($index[$currentIndex ]) ) {
            if($currentIndex === 0 && current($index[0]) === false) {
                return;
            }
        
            $currentIndex--;
            
            next($index[$currentIndex]);
        }
        
        while($currentIndex != $maxIndex) {
            
            $currentIndex++;
            reset( $index[$currentIndex] );
            
        }
        
        $currentValues = [];
        
        foreach($index as $currentArray) {
            $currentValues[] = current($currentArray);
        }
        
        call_user_func($callback,$currentValues);
        
        next($index[$currentIndex]);    
    }
}

//Recursive version of above. Needs some cleanup here, check out performance benefits (if any)
//Of passing $values by reference.
//To Call this function initially, values should be an empty array. 
//Sample call: recursiveTraverse([[0,1],[0,1]], [], 'someFunctionThatTakesAnArrayAsAnArgument'])
//$array is expected to be an array of arrays.
//$callback is expected to be a function name that takes an array as an argument.
function recursiveTraverse($array, $values, callable $callback)
{
    if( count($values) == count($array) ) {
        call_user_func($callback, $values);    
        return;
    }

    $myArray = current($array);

    foreach( $myArray as $value) {        
        next($array);
        $values[] = $value;
        recursiveTraverse($array, $values, $callback);
        array_pop($values);
    }
}

//Sample usage follows

function sum($array) {
    $sum = 0;
    
    foreach($array as $value) {
        $sum += $value;
    }
    
    echo implode(" ", $array);
	
    echo " : Sum $sum";
    echo "\n";
}

echo "Iterative Traverse:\n";

traverse([
    [0,1,2],
    [0,1,2],
    [0,1,2],
], 'sum');

echo "\n\n";
echo "Recursive Traverse:\n";

recursiveTraverse([
    [0,1,2],
    [0,1,2],
    [0,1,2],
], [], 'sum');

?>