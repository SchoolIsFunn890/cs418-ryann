<?php
    header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none';");
    include "index.html"; 

?>
