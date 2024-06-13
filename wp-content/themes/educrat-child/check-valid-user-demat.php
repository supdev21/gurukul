<?php
/*{
  "realm": "debroking",
  "auth-server-url": "https://signin.definedgebroking.com/auth",
  "ssl-required": "external",
  "resource": "GURUKUL",
  "credentials": {
    "secret": "lokHHDDBDWh7VPhKNfqedRNEPKslJ5s3"
  },
  "confidential-port": 0
}
Client ID : GURUKUL
Client Secret Key : lokHHDDBDWh7VPhKNfqedRNEPKslJ5s3
Login Endpoint URL : https://signin.definedgebroking.com/auth/realms/debroking/protocol/openid-connect/auth
Userinfo Endpoint URL : https://signin.definedgebroking.com/auth/realms/debroking/protocol/openid-connect/userinfo
Token Validation Endpoint URL : https://signin.definedgebroking.com/auth/realms/debroking/protocol/openid-connect/token
End Session Endpoint URL : http://gurukul.definedge.local:9096
*/



// Check if the checkbox is checked
if (isset($_POST['sso_login'])) {

    // Keycloak configuration
    $keycloak_config = array(
        "realm" => "debroking",
        "auth-server-url" => "https://signin.definedgebroking.com/auth",
        "ssl-required" => "external",
        "resource" => "GURUKUL",
        "credentials" => array(
            "secret" => "lokHHDDBDWh7VPhKNfqedRNEPKslJ5s3"
        ),
        "confidential-port" => 0
    );

    // Function to redirect to Keycloak login page
    function redirect_to_keycloak_login($keycloak_config) {
        $login_url = $keycloak_config['auth-server-url'] . "/realms/" . $keycloak_config['realm'] . "/protocol/openid-connect/auth";
        $params = array(
            'client_id' => $keycloak_config['resource'],
            'redirect_uri' => 'http://gurukul.definedge.local:9096', // Replace with your redirect URI
            'response_type' => 'code',
            'scope' => 'openid'
        );
        $login_url .= '?' . http_build_query($params);
        header('Location: ' . $login_url);
        exit;
    }

    // Check if the SSO response code is received
    if (isset($_GET['code'])) {
        // Process the SSO response code
        $code = $_GET['code'];

        // Exchange the code for tokens
        $token_url = $keycloak_config['auth-server-url'] . "/realms/" . $keycloak_config['realm'] . "/protocol/openid-connect/token";
        $token_params = array(
            'grant_type' => 'authorization_code',
            'client_id' => $keycloak_config['resource'],
            'client_secret' => $keycloak_config['credentials']['secret'],
            'redirect_uri' => 'http://gurukul.definedge.local:9096', // Replace with your redirect URI
            'code' => $code
        );

        // Send POST request to exchange code for tokens
        $ch = curl_init($token_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $token_response = curl_exec($ch);
        curl_close($ch);

        // Parse token response
        $token_data = json_decode($token_response, true);

        // Assuming you have retrieved the user's unique details from the token response
        $access_token = $token_data['access_token'];
        $id_token = $token_data['id_token'];

        // Now you can use these tokens as needed
        // For example, you can decode the ID token to extract user information
        $decoded_id_token = base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $id_token)[1])));
        $user_info = json_decode($decoded_id_token, true);

        // Handle user authentication and authorization using the obtained tokens
        // You may set session variables, create user accounts, etc.

        // Redirect the user to the desired page
        header('Location: http://gurukul.definedge.local:9096/checkout');
        exit;
    }

    // If not logged in, redirect to Keycloak login page
    redirect_to_keycloak_login($keycloak_config);

}
?>
