Simple decoder of bencoded data.  
Language: php.  
Procedural style.  
Bencode data must begin with a dictionary or a list.  
Usage example #1:  
    Your php script:  
    &lt;?php  
    require_once 'bencode_decoder.php';  
    $s='d3:cow3:moo4:spam4:eggse';    // your input bencoded data  
    $a=bencode_decoder($s);  
    print '&lt;pre&gt;';  
    print_r($a);  
    print '&lt;/pre&gt;';      
    ?&gt;  
      
    Output:  
    Array  
    (  
        [cow] => moo  
        [spam] => eggs  
    )
Usage example #2:
![alt text](https://github.com/johnsmith3500/bencode/blob/master/bencode.png)
