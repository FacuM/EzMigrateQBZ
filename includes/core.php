<?php

require_once 'constants.php';

$authToken = '';

/**
 * getHeaders
 * 
 * Get the headers of the API.
 *
 * @return array
 */
function getHeaders() {
    global $authToken;

    $headers = [
        'x-app-id: ' . APP_ID,
        'content-type: application/x-www-form-urlencoded'
    ];

    if ($authToken) {
        $headers[] = 'x-user-auth-token: ' . $authToken;
    }

    return $headers;
}

/**
 * request
 * 
 * Make a request to Qobuz's API.
 *
 * @param  string       $url        URL to request
 * @param  string       $method     HTTP method to use
 * @param  array|null   $data       Data to send
 * 
 * @return array|null
 */
function request($url, $method = 'GET', $data = null) {
    $ch = curl_init();

    $url = BASE_URL . $url;

    if ($data && $method == 'GET') {
        $url .= '?' . http_build_query($data);
    }

    curl_setopt($ch, CURLOPT_URL,            $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);

    curl_setopt($ch, CURLOPT_HTTPHEADER,     getHeaders());

    if ($data && $method != 'GET') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $result = curl_exec($ch);

    if (curl_errno($ch) || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
        throw new Exception('An unexpected error has occurred: ' . curl_error($ch) . ' - ' . $result);
    }

    curl_close($ch);

    $result = json_decode($result, true);

    if (
        !isset($result['status'])
        ||
        $result['status'] == 'success'
    ) {
        return $result;
    }

    return null;
}

/**
 * login
 *
 * @param  string $username
 * @param  string $password
 * 
 * @return void
 */
function login($username, $password) {
    global $authToken;

    $result = request('/user/login', 'POST', [
        'username'  => $username,
        'email'     => $username,
        'password'  => md5($password),
        'extra'     => 'partner'
    ]);

    if (isset($result['user_auth_token'])) {
        $authToken = $result['user_auth_token'];
    }
}

/**
 * setup
 * 
 * Setup the application.
 *
 * @return void
 */
function setup() {
    if (!file_exists(EXPORT_PATH)) {
        mkdir(EXPORT_PATH, 0777, true);
    }

    $username = '';
    $password = '';

    while (empty($username) || empty($password)) {
        print 'Type your username or email address: ';
        $username = trim(fgets(STDIN));
        
        print 'Type your password: ';
        $password = trim(fgets(STDIN));

        if (empty($username) || empty($password)) {
            print 'Please enter both username and password.' . PHP_EOL;

            $username = '';
            $password = '';

            continue;
        }

        print 'Logging in... ';

        try {
            login($username, $password);

            print 'OK!' . PHP_EOL;
        } catch (Exception $e) {
            print
                PHP_EOL . 
                PHP_EOL .
                'Authentication failed: ' . $e->getMessage() . PHP_EOL;

            $username = '';
            $password = '';
        }
    }
}

?>