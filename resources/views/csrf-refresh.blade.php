<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSRF Token Refresh</title>
</head>
<body>
    <h1>Refreshing your session...</h1>
    <p>Please wait while we redirect you.</p>
    
    <form id="refresh-form" method="POST" action="{{ url()->previous() }}">
        @csrf
    </form>
    
    <script>
        // Auto-submit the form
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('refresh-form').submit();
        });
    </script>
</body>
</html>
