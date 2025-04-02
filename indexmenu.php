<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant System</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        table th:first-child {
            width: 100px;
        }
    </style>
</head>

<body>
    <h2>Customer List</h2>
    <table id="customer_list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>City</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div>
        <h3>Add Customer</h3>
        <label>Name:</label> <input type="text" id="customer_name"><br>
        <label>City:</label> <input type="text" id="customer_city"><br>
        <button onclick="addCustomer()">Add Customer</button>
    </div>

    <hr>

    <h2>Menu List</h2>
    <table id="menu_list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Menu Name</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div>
        <h3>Add Menu</h3>
        <label>Menu Name:</label> <input type="text" id="menu_name"><br>
        <label>Price:</label> <input type="number" id="menu_price"><br>
        <button onclick="addMenu()">Add Menu</button>
    </div>

    <script>
        async function loadCustomer() {
            try {
                let response = await fetch('customer.php?list');
                let data = await response.json();
                let tbody = '';
                for (let customer of data) {
                    tbody += `<tr>
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.city}</td>
                              </tr>`;
                }
                document.querySelector('#customer_list tbody').innerHTML = tbody;
            } catch (error) {
                console.error("Error loading customers:", error);
            }
        }

        async function addCustomer() {
            let name = document.querySelector('#customer_name').value.trim();
            let city = document.querySelector('#customer_city').value.trim();
            if (name === "" || city === "") {
                alert("Please enter valid customer details.");
                return;
            }

            let data = { name, city };

            try {
                let response = await fetch('customer.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                let result = await response.json();
                alert(result.success || result.error);
                document.querySelector('#customer_name').value = '';
                document.querySelector('#customer_city').value = '';
                loadCustomer();
            } catch (error) {
                console.error("Error adding customer:", error);
            }
        }

        async function loadMenu() {
            try {
                let response = await fetch('menu.php?list');
                let data = await response.json();
                let tbody = '';
                for (let manu of data) {
                    tbody += `<tr>
                                <td>${manu.manu_id}</td>
                                <td>${manu.manu_name}</td>
                                <td>${manu.price}</td>
                              </tr>`;
                }
                document.querySelector('#menu_list tbody').innerHTML = tbody;
            } catch (error) {
                console.error("Error loading menu:", error);
            }
        }

        async function addMenu() {
            let manu_name = document.querySelector('#menu_name').value.trim();
            let price = document.querySelector('#menu_price').value.trim();
            if (manu_name === "" || price === "" || isNaN(price) || price <= 0) {
                alert("Please enter valid menu details.");
                return;
            }

            let data = { manu_name, price };

            try {
                let response = await fetch('menu.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                let result = await response.json();
                alert(result.success || result.error);
                document.querySelector('#menu_name').value = '';
                document.querySelector('#menu_price').value = '';
                loadMenu();
            } catch (error) {
                console.error("Error adding menu:", error);
            }
        }

        window.onload = function () {
            loadCustomer();
            loadMenu();
        };
    </script>

</body>
</html>
