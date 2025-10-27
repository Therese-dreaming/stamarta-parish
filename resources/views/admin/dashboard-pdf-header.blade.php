<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #0d5c2f 0%, #0a4a26 100%);
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .header-logo {
            width: 35px;
            height: 35px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            color: white;
        }
        .header p {
            font-size: 10px;
            margin: 2px 0 0 0;
            color: white;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <img src="{{ public_path('images/church-logo.png') }}" alt="Logo" class="header-logo">
            <div>
                <h1>Santa Marta Parish</h1>
                <p>San Roque</p>
            </div>
        </div>
    </div>
</body>
</html>
