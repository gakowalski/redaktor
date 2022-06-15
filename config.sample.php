<?php

$config = [
  'security_password_hash' => hash('sha256', 'test'),  //< hash of password to be used as 'pass' parameter
  'path_limit' => 'www', // all paths MUST contain this
];
