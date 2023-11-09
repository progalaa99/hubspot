<h1>Home page</h1>
<a href="register.php">register</a>
<h2>Get Data Api from https://catfact.ninja/fact</h2>
<?php
// Initialize cURL session
$ch = curl_init();

// Set the API endpoint
$api_url = 'https://catfact.ninja/fact';

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Execute the cURL session
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Decode the JSON response
    $data = json_decode($response);

    // Check if the API request was successful
    if ($data && isset($data->fact) && isset($data->length)) {
        echo '<h3>'.'Cat Fact: ' . $data->fact.'</h3>';
        echo '<h3>'.'length: ' . $data->length.'</h3>';
    } else {
        echo 'Unable to retrieve cat fact.';
    }
}

// Close the cURL session
curl_close($ch);
?>
