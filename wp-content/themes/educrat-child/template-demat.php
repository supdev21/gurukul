<?php
/**
 * The template Demat Account Users
 *
 * @package WordPress
 * @subpackage Educrat
 * @since Educrat 1.0
 */
/*
*Template Name: Demat Account Users
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
get_header();

?>

    <section class="page-404 justify-content-center flex-middle">
        <div id="main-container" class="inner">
            <div id="main-content" class="main-page">
                <section class="error-404 not-found clearfix">
                    <div class="container">
                        
                        <div class="row d-md-flex align-items-center">
                           

<?php
/*// OAuth 2.0 server configuration
$authorizationEndpoint = 'https://signin.definedgebroking.com/auth/realms/debroking/protocol/openid-connect/auth';
$tokenEndpoint = 'https://signin.definedgebroking.com/auth/realms/debroking/protocol/openid-connect/token';
$clientID = 'GURUKUL';
$clientSecret = 'lokHHDDBDWh7VPhKNfqedRNEPKslJ5s3';
$redirectURI = 'http://gurukul.definedge.local:9096/validate-demat-account/';
$scope = 'email profile openid offline_access'; // adjust scopes according to your requirements

// Step 1: Redirect user to authorization endpoint
if (!isset($_GET['code'])) {
    $authorizationURL = $authorizationEndpoint . '?' . http_build_query([
        'response_type' => 'code',
        'client_id' => $clientID,
        'redirect_uri' => $redirectURI,
        'scope' => $scope,
    ]);
    header('Location: ' . $authorizationURL);
    exit;
}

// Step 2: Exchange authorization code for access token
$authorizationCode = $_GET['code'];
$tokenRequestData = [
    'grant_type' => 'authorization_code',
    'code' => $authorizationCode,
    'redirect_uri' => $redirectURI,
    'client_id' => $clientID,
    'client_secret' => $clientSecret,
];
$tokenRequest = curl_init($tokenEndpoint);
curl_setopt($tokenRequest, CURLOPT_POST, true);
curl_setopt($tokenRequest, CURLOPT_POSTFIELDS, http_build_query($tokenRequestData));
curl_setopt($tokenRequest, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($tokenRequest);
$http_status = curl_getinfo($tokenRequest, CURLINFO_HTTP_CODE);
curl_close($tokenRequest);


// Check if request was successful (HTTP status 200)
if ($http_status === 200) {
    // Parse token response
    $tokenData = json_decode($response, true);
    
    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];

        // Step 3: Retrieve userinfo using access token
        $userinfoEndpoint = 'https://signin.definedgebroking.com/auth/realms/debroking/protocol/openid-connect/userinfo';

        // Create HTTP request to retrieve userinfo
        $ch = curl_init($userinfoEndpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute request and get response
        $userinfoResponse = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL resource
        curl_close($ch);

        // Check if request was successful (HTTP status 200)
        if ($http_status === 200) {
            // Parse userinfo response
            $userInfo = json_decode($userinfoResponse, true);

            // Display user information
            echo 'User Info: <pre>';
            print_r($userInfo);
            echo '</pre>';*/
/*
            $userInfo = array(
                'sub' => 'd1597daf-0c8a-4ca2-9555-e34ef22e27b5',
                'email_verified' => 1,
                'ucc' => 3100069,
                'dob' => '01/01/2000',
                'mobile' => '9158855873',
                'name' => 'Amit Mahindkar',
                'preferred_username' => 3100069,
                'given_name' => 'Amit',
                'pan' => 'AESPJ2212K',
                'family_name' => 'Mahindkar',
                'email' => 'ankita.bhor@definedge.com',
                'user_groups' => array()
            );

            $json = json_encode($userInfo);
            // Output the JSON string
            echo $json;


            echo 'User Info: <pre>';
            print_r($userInfo);
            echo '</pre>';

            // Set transient with the "ucc" value
            set_transient('ucc_value', $userInfo['ucc'], 3600); // Assuming $data is the array containing your data*/
            
        /*} else {
            // Request failed, display error message
            echo 'Error fetching userinfo: HTTP Status ' . $http_status . '<br>';
            echo 'Response: ' . $userinfoResponse;
        }
    } else {
        // Access token not found in token response
        echo 'Error: Access token not found in token response';
    }
} else {
    // Token request failed, display error message
    echo 'Error fetching access token: HTTP Status ' . $http_status . '<br>';
    echo 'Response: ' . $response;
}
*/

?>




                        </div>

                        
                    </div>
                </section><!-- .error-404 -->
            </div><!-- .content-area -->
        </div>
    </section>
    
<?php
/*  ===== OUTPUT ==========*/
/*
Array
(
    [sub] => d1597daf-0c8a-4ca2-9555-e34ef22e27b5
    [email_verified] => 1
    [ucc] => 3100069
    [dob] => 01/01/2000
    [mobile] => 9158855873
    [name] => Amit Mahindkar
    [preferred_username] => 3100069
    [given_name] => Amit
    [pan] => AESPJ2212K
    [family_name] => Mahindkar
    [email] => ankita.bhor@definedge.com
    [user_groups] => Array
        (
        )

)
*/
/*  ===== OUTPUT ==========*/
get_footer(); ?>