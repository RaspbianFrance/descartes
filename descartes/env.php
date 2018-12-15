<?php
    /*
     * Define Descartes env
     */
    $http_dir_path = '/descartes'; //Path we need to put after servername in url to access app
    $http_protocol = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://';
    $http_server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
    $http_server_port = isset($_SERVER['SERVER_PORT']) ? ($_SERVER['SERVER_PORT'] == 80) ? '' : $_SERVER['SERVER_PORT'] : '';

    $env = [
        //Global http and file path
        'HTTP_DIR_PATH' => $http_dir_path,
        'HTTP_PROTOCOL' => $http_protocol,
        'HTTP_SERVER_NAME' => $http_server_name,
        'HTTP_SERVER_PORT' => $http_server_port,
        'PWD' => substr(__DIR__, 0, strrpos(__DIR__, '/')),
        'HTTP_PWD' => $http_protocol . $http_server_name . $http_server_port . $http_dir_path,

        //path of back resources
        'PWD_CONTROLLER' => PWD . '/controllers', //Controllers dir
        'PWD_MODEL' => PWD . '/models', //Models dir
        'PWD_TEMPLATES' => PWD . '/templates', //Templates dir

        //path of front resources
        'PWD_ASSETS' => PWD . '/assets', //Assets dir
        'HTTP_PWD_ASSETS' => HTTP_PWD . '/assets', //HTTP path of asset dir

        //images
        'PWD_IMG' => PWD_ASSETS . '/img',
        'HTTP_PWD_IMG' => HTTP_PWD_ASSETS . '/img', 

        //css
        'PWD_CSS' => PWD_ASSETS . '/css', 
        'HTTP_PWD_CSS' => HTTP_PWD_ASSETS . '/css', 

        //javascript
        'PWD_JS' => PWD_ASSETS . '/js', 
        'HTTP_PWD_JS' => HTTP_PWD_ASSETS . '/js', 

        //fonts
        'PWD_FONT' => PWD_ASSETS . '/font', 
        'HTTP_PWD_FONT' => HTTP_PWD_ASSETS . '/font', 
    ];

