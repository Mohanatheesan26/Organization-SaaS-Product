<!-- resources/views/layouts/main.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Product Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden { 
            display: none; 
        }
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-text {
            margin-bottom: 0.75rem;
        }

        .btn {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>SaaS Product Management</h1>
    </header>
    <main class="container mt-5">
        @yield('content')
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @stack('scripts')
</body>
</html>
