<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Gestion des Employés</h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Poste</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employeeTable"></tbody>
        </table>
        <button class="btn btn-primary" onclick="addEmployee()">Ajouter un employé</button>
    </div>
    <script>
        function loadEmployees() {
            fetch('employees.php')
                .then(response => response.json())
                .then(data => {
                    let rows = '';
                    data.forEach(emp => {
                        rows += `<tr>
                                    <td>${emp.id}</td>
                                    <td>${emp.name}</td>
                                    <td>${emp.email}</td>
                                    <td>${emp.position}</td>
                                    <td>
                                        <button class='btn btn-warning btn-sm' onclick='editEmployee(${emp.id})'>Modifier</button>
                                        <button class='btn btn-danger btn-sm' onclick='deleteEmployee(${emp.id})'>Supprimer</button>
                                    </td>
                                </tr>`;
                    });
                    document.getElementById('employeeTable').innerHTML = rows;
                });
        }
        function addEmployee() {
            let name = prompt("Nom de l'employé :");
            let email = prompt("Email :");
            let position = prompt("Poste :");
            fetch('employees.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({name, email, position})
            }).then(() => loadEmployees());
        }
        function deleteEmployee(id) {
            fetch('employees.php', {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id})
            }).then(() => loadEmployees());
        }
        window.onload = loadEmployees;
    </script>
</body>
</html>
