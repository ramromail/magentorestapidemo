<?php

echo '<pre>'.print_r($_ENV['RestAPIUSER'],1).'</pre><br/>';
echo '<pre>'.print_r($_ENV['RestAPIPASS'],1).'</pre><br/>';
echo '<pre>'.print_r($_ENV['RestAPIHOST'],1).'</pre><br/>';
echo '<pre>'.print_r($_GET,1).'</pre><br/>';
echo '<pre>'.print_r($_POST,1).'</pre><br/>';
echo '<pre>'.print_r($_SERVER,1).'</pre><br/>';

echo getenv('RestAPIUSER').' '.getenv('RestAPIPASS').' '.getenv('RestAPIHOST');