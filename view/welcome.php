<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Mo-Helper</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #222;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: row;
            background-color: #333;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 800px;
            text-align: center;
        }

        .container img {
            width: 150px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .container h1 {
            margin-bottom: 20px;
            color: #fff;
            font-size: 2rem;
        }

        .container p {
            margin-bottom: 20px;
            color: #ddd;
            font-size: 1.2rem;
        }

        .container button {
            padding: 12px 24px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1.1rem;
        }

        .container button:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #888;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
                align-items: center;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <img src="https://i.pinimg.com/550x/ab/2e/0e/ab2e0e99e3b89ee4efb89097fb7ec1dd.jpg" alt="Foto Anda">
        <div>
            <h1>Selamat Datang di Mo-Helper</h1>
            <p>Halo, nama saya Arifin. Saya adalah pengembang web yang bersemangat</p>
        </div>
    </div>


</body>

</html>