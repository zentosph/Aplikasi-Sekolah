<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Full screen navy background */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
           
        }

        /* Centered form container */
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            position: relative;
        }

        /* School logo */
        .school-logo {
            display: block;
            margin: 0 auto 10px;
            width: 70px; /* Reduced size */
            height: 70px;
        }

        /* Form title */
        .login-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px; /* Reduced margin */
            font-size: 22px; /* Reduced font size */
            color: #007bff;
        }

        /* Input styles */
        .form-control {
            border-radius: 30px;
            padding: 12px; /* Reduced padding */
            font-size: 15px; /* Slightly smaller font */
        }

        /* Submit button styles */
        .btn-primary {
            border-radius: 30px;
            font-size: 15px;
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            border: none;
            margin-top: 10px;
        }

        /* Additional link */
        .additional-link {
            text-align: center;
            margin-top: 10px; /* Reduced margin */
        }

        .additional-link a {
            color: #007bff;
        }

        /* Logo container styling */
        .logosekolah {
            background-color: navy; /* Match the body background color */
            width: 100%; /* Use full width of the card */
            padding: 10px 0; /* Add vertical padding if needed */
            border-radius: 10px 10px 10px 10px; /* Rounded top corners */
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- School Logo -->
        <div class="logosekolah">
            <img src="<?= base_url('images/'.$setting->login) ?>" alt="School Logo" class="school-logo">
        </div>

        <form action="<?= base_url('home/aksi_login') ?>" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your ID or email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group" id="recaptchaContainer" style="display: none;">
                <div class="g-recaptcha" data-sitekey="6LeKfiAqAAAAAIYfzHJCoT6fOpGTpktdJza3fixn"></div>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="additional-link">
                <a href="#">Forgot password?</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const recaptchaContainer = document.getElementById('recaptchaContainer');

        // Check internet connection and show/hide reCAPTCHA accordingly
        if (navigator.onLine) {
            recaptchaContainer.style.display = 'block';
        } else {
            // Handle the case for fallback to image CAPTCHA if needed
        }
    });
    </script>

</body>
</html>
