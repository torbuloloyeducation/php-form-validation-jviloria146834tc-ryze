<?php
$nameErr = $emailErr = $genderErr = $phoneErr = $websiteErr = $passErr = $confPassErr = $termsErr = "";
$name = $email = $website = $comment = $gender = $phone = $pass = $confPass = "";
$submitted = false;

$counter = isset($_POST['counter']) ? (int)$_POST['counter'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;
    $counter++;

    // 1. Name Validation
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    // 2. Email Validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Exercise 1: Phone Number Validation
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[+]?[0-9 \-]{7,15}$/", $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    // Exercise 2: Website Validation
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }

    // Exercise 3: Password Validation
    if (empty($_POST["pass"])) {
        $passErr = "Password is required";
    } elseif (strlen($_POST["pass"]) < 8) {
        $passErr = "Password must be at least 8 characters";
    }

    if (empty($_POST["confPass"])) {
        $confPassErr = "Please confirm your password";
    } elseif ($_POST["pass"] !== $_POST["confPass"]) {
        $confPassErr = "Passwords do not match";
    }

    // Comment field
    $comment = empty($_POST["comment"]) ? "" : test_input($_POST["comment"]);

    // 4. Gender Validation
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Exercise 4: Terms and Conditions
    if (!isset($_POST['terms'])) {
        $termsErr = "You must agree to the terms and conditions";
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$formValid = $submitted && empty($nameErr) && empty($emailErr) && empty($genderErr) && 
             empty($phoneErr) && empty($websiteErr) && empty($passErr) && 
             empty($confPassErr) && empty($termsErr);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Lab - Validation</title>
    <style>
        :root { --primary-color: #4f46e5; --primary-hover: #4338ca; --bg-color: #f9fafb; --card-bg: #ffffff; --text-main: #1f2937; --text-muted: #6b7280; --error-red: #ef4444; --success-green: #10b981; --border-color: #e5e7eb; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); color: var(--text-main); display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .form-container { background: var(--card-bg); padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); width: 100%; max-width: 500px; }
        .field-row { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { font-weight: 600; font-size: 0.875rem; margin-bottom: 4px; }
        input[type="text"], input[type="password"], textarea { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; box-sizing: border-box; }
        .error { color: var(--error-red); font-size: 0.8rem; margin-top: 4px; }
        .radio-group { display: flex; gap: 15px; }
        button { width: 100%; background: var(--primary-color); color: white; border: none; padding: 12px; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .success-box { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .output-box { background: #f3f4f6; border: 1px solid var(--border-color); padding: 15px; border-radius: 8px; margin-top: 20px; }
        .attempt-count { font-size: 0.85rem; color: var(--text-muted); text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Registration Form</h2>
    <p class="attempt-count">Submission attempt: <strong><?= $counter ?></strong></p>

    <?php if ($formValid): ?>
        <div class="success-box">Form submitted successfully!</div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <!-- Hidden field for Exercise 5 -->
        <input type="hidden" name="counter" value="<?= $counter ?>">

        <div class="field-row">
            <label>Name *</label>
            <input type="text" name="name" value="<?= $name ?>">
            <span class="error"><?= $nameErr ?></span>
        </div>

        <div class="field-row">
            <label>E-mail *</label>
            <input type="text" name="email" value="<?= $email ?>">
            <span class="error"><?= $emailErr ?></span>
        </div>

        <div class="field-row">
            <label>Phone Number *</label>
            <input type="text" name="phone" placeholder="+1 234 567 890" value="<?= $phone ?>">
            <span class="error"><?= $phoneErr ?></span>
        </div>

        <div class="field-row">
            <label>Website</label>
            <input type="text" name="website" placeholder="https://example.com" value="<?= $website ?>">
            <span class="error"><?= $websiteErr ?></span>
        </div>

        <!-- Exercise 3: Password Fields -->
        <div class="field-row">
            <label>Password *</label>
            <input type="password" name="pass" value="">
            <span class="error"><?= $passErr ?></span>
        </div>

        <div class="field-row">
            <label>Confirm Password *</label>
            <input type="password" name="confPass" value="">
            <span class="error"><?= $confPassErr ?></span>
        </div>

        <div class="field-row">
            <label>Gender *</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="Female" <?= ($gender == "Female") ? "checked" : "" ?>> Female</label>
                <label><input type="radio" name="gender" value="Male" <?= ($gender == "Male") ? "checked" : "" ?>> Male</label>
            </div>
            <span class="error"><?= $genderErr ?></span>
        </div>

        <!-- Exercise 4: Terms Checkbox -->
        <div class="field-row">
            <label style="font-weight: normal;">
                <input type="checkbox" name="terms" <?= isset($_POST['terms']) ? "checked" : "" ?>> 
                I agree to the terms and conditions *
            </label>
            <span class="error"><?= $termsErr ?></span>
        </div>

        <button type="submit">Submit Registration</button>
    </form>

    <?php if ($submitted && $formValid): ?>
        <div class="output-box">
            <h3>Your Verified Input:</h3>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Phone:</strong> <?= $phone ?></p>
            <?php if ($website): ?><p><strong>Website:</strong> <?= $website ?></p><?php endif; ?>
            <p><strong>Gender:</strong> <?= $gender ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>