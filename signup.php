<?php
include 'db.php';
include 'create_tables.php'; // Include the table creation script

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];

    // Validate form data (you can add more validation as needed)
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($dob) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "This email is already registered.";
            $stmt->close();
            exit;
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement to insert the user data into the database
    $sql = "INSERT INTO users (firstname, lastname, email, phone, dob, password) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssssss", $firstname, $lastname, $email, $phone, $dob, $hashed_password);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page or display a success message
            header("Location: login_signup.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
    }

    // Close connection
    $conn->close();
}
?>