<?php

namespace ls\core;

# Locale url chunk
define('LOCALE', 'locale');

# Determine resource internet media type by it extension
# Values: true, false
# Required: yes
define('DETERMINE_IMT_BY_EXTENSION', true);

# Define the base url
# Example: http://www.example.com
# Required: false
#define('BASE_URL', 'http://localhost:8080');

# Declares the active state of the application
# Values: true, false
# Required: no
wput('Default:App', '_test');

# Declare internet media types used by LeosPHP
# to set proper mime type for resource files
# Required: yes
wput(
    'Resources:IMTMap',
    array(
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'js' => 'application/javascript',
        'css' => 'text/css',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf'
    )
);