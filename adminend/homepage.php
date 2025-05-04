<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    </style>
</head>
<body>
    <!-- <div id="item-lists">
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
        <table> -->
            <!-- ...existing table content... -->
        <!-- </table>
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
    </script> -->
</body>
</html>
