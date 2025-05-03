<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        #footer p {
            margin: 5px 0;
        }
        #footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        #footer a:hover {
            text-decoration: underline;
        }
        @media (max-width: 1024px) {
            #footer {
                font-size: 1em;
                padding: 10px 0;
            }
        }
        @media (max-width: 768px) {
            #footer {
                font-size: 0.9em;
                padding: 8px 0;
            }
        }
        @media (max-width: 480px) {
            #footer {
                font-size: 0.8em;
                padding: 6px 0;
            }
        }
        @media (max-width: 320px) {
            #footer {
                font-size: 0.7em;
                padding: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div id="footer">
        <p>&copy; 2023 Your Company. All rights reserved.</p>
        <p>Privacy Policy | Terms of Service</p>
        <p>Follow us on: 
            <a href="#">Facebook</a> | 
            <a href="#">Twitter</a> | 
            <a href="#">Instagram</a>
        </p>    
    </div>
</body>
</html>
