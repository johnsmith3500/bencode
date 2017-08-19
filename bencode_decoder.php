<?php
// bencode_decoder.php - decoder of bencoded data
// Input: php string of bencoded data. The string must begin with a dictionary or a list!!!
// Output: php array.
// bencode_decoder() - main function.
// unlist() and undict() - auxiliary functions. They use recursion.

function bencode_decoder($s)
{
	global $i, $str;
	$i=0;
	$str=$s;
	$str=trim($str);	// strip off whitespace
	if($str[0] == 'l')
		$b=unlist();
	else if($str[0] == 'd')
		$b=undict();
	else
	{
		print '<p>ERROR! Unknown format.</p>';
		return false;
	}	
	unset($GLOBALS['str']);
	unset($GLOBALS['i']);
	return $b;
}

function unlist()
{
	global $i, $str;
	$num_l=0;	// number of l (list) in this instance of unlist()
	while(isset($str[$i]) && ($str[$i]!='e'))	// iterate through $str
	{
		$e=$str[$i];	// current char in $str
		if($e == 'l')
		{
			
			$num_l++;
			if($num_l == 1) 	// first list - need create empty array a
			{
				$i++;
				$a=array();
				continue;	// go to the next char right away
			}
			else 	// not first list - we have sublist
			{
				$e=unlist();
				$i++;
			}
		}
		else if($e == 'd')
		{
			$e=undict();
			$i++;
		}
		else if(is_numeric($e))	// we have a substring (e.g. 4:spam)
		{
			$colon_pos=strpos($str, ':', $i);	// seek nearest ':' position
			$num_str=substr($str, $i, ($colon_pos-$i)); 	// extract length of our substring
			$e=substr($str, ($colon_pos+1), $num_str);	// extract our substring
			$i=$colon_pos+$num_str+1;	// $i adjusting after we get our substring
		}
		else if($e == 'i')	// we have integer
		{
			$i++; // go to numeric data
			$e_nearest=strpos($str, 'e', $i);	// position of nearest 'e'
			$e=substr($str, $i, ($e_nearest-$i));	// extract int
			$i=$e_nearest+1; // adjusting $i
		}				
		$a[]=$e;
	}
	return $a;
}

function undict()
{
	global $i, $str;
	$num_d=0;	// number of d (dict) in this instance of undict()
	while(isset($str[$i]) && ($str[$i]!='e'))	// iterate through $str
	{
		$e=$str[$i];	// current char in $str
		if($e == 'd')
		{
			
			$num_d++;
			if($num_d == 1) 	// first dict - need create empty array a
			{
				$i++;
				$a=array();
				$needkey=1;
				continue;	// go to the next char right away
			}
			else 	// not first dict - we have subdict
			{
				$e=undict();
				$i++;
			}
		}
		else if($e == 'l')	// we have list
		{
			$e=unlist();
			$i++;
		}
		else if(is_numeric($e))	// we have a substring (e.g. 4:spam)
		{
			$colon_pos=strpos($str, ':', $i);	// seek nearest ':' position
			$num_str=substr($str, $i, ($colon_pos-$i)); 	// extract length of our substring
			$e=substr($str, ($colon_pos+1), $num_str);	// extract our substring
			$i=$colon_pos+$num_str+1;	// $i adjusting after we get our substring
		}
		else if($e == 'i')	// we have integer
		{
			$i++; // go to numeric data
			$e_nearest=strpos($str, 'e', $i);	// position of nearest 'e'
			$e=substr($str, $i, ($e_nearest-$i));	// extract int
			$i=$e_nearest+1; // adjusting $i
		}				
		if($needkey==1)
		{
			$key=$e;
			$needkey=0;
		}
		else
		{
			$a[$key]=$e;
			$needkey=1;
		}
	}
	return $a;
}
?>
