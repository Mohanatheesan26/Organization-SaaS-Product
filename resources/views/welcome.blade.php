<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Product Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .center-content {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container center-content">
        <div>
            <h1>Welcome to Product Management</h1>
            <p class="lead">Manage your organizations, locations, and devices efficiently.</p>
            <a href="{{ url('/organizations') }}" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</body>
</html>
