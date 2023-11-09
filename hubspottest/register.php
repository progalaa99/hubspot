<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
</head>

<body>

    <h2>User Registration Form</h2>

    <form action="register.php" method="post">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Register">
    </form>

</body>

</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare data for HubSpot API
    $data = array(
        "properties" => array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $hashedPassword
        )
    );

    // Make API request (you would replace this with your actual code to interact with HubSpot API)
    // Example using cURL
    $url = "https://api.hubapi.com/crm/v3/objects/contacts";
    $accessToken = "pat-eu1-ccba8985-3618-4507-9d74-7497261ecf08";
    $headers = array(
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if ($response === false) {
        echo "Error making API request: " . curl_error($ch);
    } else {
        echo "Registration successful! HubSpot response: " . $response;
        header("Location: login.php");
        exit();
    }

    curl_close($ch);
}
?>