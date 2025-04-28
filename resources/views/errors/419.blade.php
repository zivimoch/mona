<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f8f8;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            background: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 3em;
            color: #333;
        }

        p {
            font-size: 1.2em;
            color: #666;
            margin: 20px 0;
        }

        button {
            padding: 10px 20px;
            font-size: 1em;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .fade-out {
            animation: fadeOut 0.5s forwards;
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <h1>419 Page Expired</h1>
        <p>Halaman ini Expired karena sesi anda telah berganti. Klik tombol dibawah ini untuk memperbarui sesi.</p>
        <button onclick="refreshPage()">Refresh Page</button>
    </div>

    <script>
        function refreshPage() {
            const container = document.getElementById('container');
            container.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = '{{ request()->fullUrl() }}';
            }, 500);
        }
    </script>
</body>
</html>
