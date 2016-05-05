<?php
return array(
    'pdf' => array(
        'enabled' => true,
        'binary' => base_path() . env('PDF_TOOL_PATH'),
        'timeout' => false,
        'options' => array(),
    ),
);