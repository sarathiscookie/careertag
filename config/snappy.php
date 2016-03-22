<?php
return array(
    'pdf' => array(
        'enabled' => true,
        'binary' => env(base_path() . 'PDF_TOOL_PATH', '/usr/bin/wkhtmltopdf.sh'),
        'timeout' => false,
        'options' => array(),
    ),
);