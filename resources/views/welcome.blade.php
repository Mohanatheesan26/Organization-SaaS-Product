<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SaaS Product Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Welcome to SaaS Product Management</h1>
        <p class="lead">Manage your organizations, locations, and devices efficiently.</p>
        <a href="{{ url('/organizations') }}" class="btn btn-primary">Get Started</a>
    </div>
</body>
</html>
