
<?php
use App\Security\Csrf;

// Generate the CSRF token
$csrfToken = Csrf::generateToken();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
<div>
    <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
        <input type="text" class="form-control" name="name">
        <input type="password" class="form-control" name="password">
        <button>Submmit</button>
    </form>
</div>
</body>
</html>