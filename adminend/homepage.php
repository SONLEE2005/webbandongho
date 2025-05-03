<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #item-lists ul {
            list-style-type: none;
            padding: 0;
        }
        #item-lists li {
            margin: 10px 0;
        }
        #item-lists button {
            font-size: 18px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #item-lists button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            #item-lists ul {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            #item-lists button {
                font-size: 16px;
                padding: 8px 15px;
                width: auto;
                max-width: 200px;
            }
            #table-wrapper {
                overflow-x: auto; /* Enable horizontal scrolling */
                -webkit-overflow-scrolling: touch; /* Smooth scrolling for mobile devices */
            }
            table {
                min-width: 700px; /* Ensure table doesn't shrink too much */
            }
        }
        @media (max-width: 480px) {
            #item-lists button {
                font-size: 14px;
                padding: 6px 12px;
            }
        }
    </style>
</head>
<body>
    <div id="item-lists">
        <ul>
            <li id="home"><button>Home</button></li>
            <li id="prod-lists"><button>Product Lists</button></li>
            <li id="users-lists"><button>Users Lists</button></li>
            <li id="admin-database"><button>Admin Lists</button></li>
            <li id="status"><button>Shipping Status</button></li>
            <li id="statistic"><button>Statistic</button></li>
        </ul>
    </div>
    <div id="table-wrapper">
        <table>
            <!-- ...existing table content... -->
        </table>
    </div>
    <script src="../script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('#admin-database button').addEventListener('click', function() {
                window.location.href = 'index.php?page=adminlist';
            }); 
            document.querySelector('#home button').addEventListener('click', function() {
                window.location.href = 'index.php?page=home';
            });
            document.querySelector('#prod-lists button').addEventListener('click', function() {
                window.location.href = 'index.php?page=productlist';
            });
            document.querySelector('#users-lists button').addEventListener('click', function() {
                window.location.href = 'index.php?page=userlists';
            });
            document.querySelector('#status button').addEventListener('click', function() {
                window.location.href = 'index.php?page=shippingstatus';
            });
            document.querySelector('#statistic button').addEventListener('click', function() {
                window.location.href = 'index.php?page=statistic';
            });
        });
    </script>
</body>
</html>
