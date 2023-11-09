<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login Form</title>
</head>

<body>

    <h2>User Login Form</h2>

    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>

</body>

</html>

<?php

function isPasswordRelatedToEmail($email, $enteredPassword)
{
    // Replace this with your actual HubSpot API access token
    $hubspotAccessToken = "pat-eu1-ccba8985-3618-4507-9d74-7497261ecf08";

    // HubSpot API endpoint for searching contacts by email
    $apiEndpoint = "https://api.hubapi.com/crm/v3/objects/contacts/search";

    // Prepare data for the API request
    $requestData = array(
        'filterGroups' => array(
            array(
                'filters' => array(
                    array(
                        'value' => $email,
                        'propertyName' => 'email',
                        'operator' => 'EQ',

                    ),
                ),
            ),
        ),
        'properties' => array('password'),
        'limit' => 1,
    );

    // Make API request to HubSpot API using cURL
    $ch = curl_init($apiEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            "Authorization: Bearer $hubspotAccessToken",
            "Content-Type: application/json",
        )
    );
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($statusCode == 200) {
        $responseData = json_decode($response, true);

        // Check if any contacts were found
        if (!empty($responseData['results'])) {
            // print_r($responseData['results']);
            // Check if the 'password' property exists in the response
            if (isset($responseData['results'][0]['properties']['password'])) {

                $hashedPasswordFromHubSpot = $responseData['results'][0]['properties']['password'];
                // echo $hashedPasswordFromHubSpot;
                // $enteredPasswordEncoding = mb_detect_encoding($hashedPasswordFromHubSpot);
                // echo $enteredPasswordEncoding;
                // $enteredPassword = trim($enteredPassword);
                // $hashedPasswordFromHubSpot = trim($hashedPasswordFromHubSpot);
                // Compare entered password with the hashed password from HubSpot
                // if (hash_equals($hashedPasswordFromHubSpot, crypt($enteredPassword, $hashedPasswordFromHubSpot))) {
                if (password_verify($enteredPassword, $hashedPasswordFromHubSpot)) {
                    // if ($enteredPassword === $hashedPasswordFromHubSpot) {
                    echo 'ok';
                    return true; // Password is related to the email in HubSpot
                } else {
                    echo 'not ok';
                    return false; // Incorrect password
                }
            } else {
                return false; // 'password' property not found in the response
            }
        }
    }

    return false; // Email not found or an error occurred
}

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// $emailToCheck = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
// $password = $_POST["password"];
// $enteredPassword = password_hash($password, PASSWORD_DEFAULT);
$enteredPassword = 123456789123456789;
$emailToCheck = 'user2@gmail.com';
// $enteredPassword = password_hash($password, PASSWORD_DEFAULT);
if (isPasswordRelatedToEmail($emailToCheck, $enteredPassword)) {
    echo "Password is related to the email in HubSpot.";
    header("Location: dashboard.php");
} else {
    echo "Password is not related to the email in HubSpot or an error occurred.";
}
// }
?>