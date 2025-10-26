<?php
// case_orders.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Case Orders - Denture Reports</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      display: flex;
    }

    .sidebar {
      width: 240px;
      background: url('pexels-olly-3779705.jpg') no-repeat center center;
      background-size: cover;
      height: 100vh;
      padding-top: 20px;
      position: fixed;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: black;
      text-decoration: none;
      font-weight: bold;
      border: 1px solid rgba(0, 0, 0, 0.2);
      margin: 4px 10px;
      border-radius: 8px;
      background-color: rgba(255, 255, 255, 0.7);
      transition: background-color 0.2s;
    }

    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.9);
    }

    .sidebar a.active {
      background-color: #189ab4;
      color: white;
    }

    .content {
      margin-left: 240px;
      padding: 20px;
      width: 100%;
      background: url('pexels-karolina-grabowska-6627592.jpg') no-repeat center center;
      background-size: cover;
      min-height: 100vh;
      overflow-y: auto;
    }

    h1 {
      color: #05445e;
      font-size: 2.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .search-container {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 1rem;
      gap: 0.5rem;
    }

    input[type="text"] {
      padding: 0.5rem 0.75rem;
      border: 1px solid #ccc;
      border-radius: 0.375rem;
      width: 250px;
      font-size: 0.875rem;
      outline-offset: 2px;
    }

    input[type="text"]:focus {
      outline: 2px solid #189ab4;
    }

    button.search-btn {
      background-color: #189ab4;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    button.search-btn:hover {
      background-color: #127a95;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 4px 6px rgb(0 0 0 / 0.1);
    }

    thead tr {
      background-color: #189ab4;
      color: white;
      font-weight: 700;
    }

    th,
    td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #ddd;
      text-align: left;
      white-space: nowrap;
    }

    tbody tr:hover {
      background-color: #d4f1f4;
    }

    a.assign-btn {
      background-color: #22c55e;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      transition: background-color 0.2s;
      white-space: nowrap;
      display: inline-block;
    }

    a.assign-btn:hover {
      background-color: #16a34a;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <div class="sidebar-header flex justify-between items-center px-4 font-bold text-lg text-black">
        Jeffrey Laboratory
        <button id="dark-mode-toggle" class="p-2 bg-white rounded-full shadow">🌙</button>
      </div>
      <a href="{{ url('dashboard') }}" class="menu-link">🏠 Dashboard</a>
      <a href="{{ route('billing') }}" class="menu-link">💳 Billing</a>
      <a href="{{ url('appointments') }}" class="menu-link">📅 Appointments</a>
      <a href="{{ url('schedules') }}" class="menu-link">🗓 Schedules</a>
      <a href="{{ url('deliveries') }}" class="menu-link">🚚 Deliveries</a>
      <a href="{{ url('invoices') }}" class="menu-link">🧾 Invoices</a>
      <a href="{{ url('departments') }}" class="menu-link">🏢 Departments</a>
      <a href="{{ route('technicians') }}" class="menu-link">🛠 Technicians</a>
      <a href="{{ route('riders') }}" class="menu-link">🚴 Riders</a>
      <a href="{{ route('clinics') }}" class="menu-link">🏥 Clinics</a>
    </div>
    <div class="p-4">
      <button class="w-full p-2 bg-red-500 text-white rounded">Logout</button>
    </div>
  </div>

  <!-- Content -->
  <div class="content">
    <h1>Case Orders</h1>

    <div class="search-container">
      <input type="text" placeholder="Search appointment..." />
      <button class="search-btn">Search</button>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-lg">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Dentist Name</th>
            <th>Notes</th>
            <th>Received By</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "capstone");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to fetch case orders with patient & dentist names
            $sql = "SELECT 
                        co.co_id,
                        p.name AS patient_name,
                        d.name AS dentist_name,
                        co.notes,
                        co.recieve_by,
                        co.recieve_at
                    FROM case_orders co
                    LEFT JOIN tbl_patient p ON co.patient_id = p.patient_id
                    LEFT JOIN dentists d ON p.dentist_id = d.dentist_id
                    ORDER BY co.co_id DESC";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['co_id']}</td>
                            <td>{$row['patient_name']}</td>
                            <td>{$row['dentist_name']}</td>
                            <td>{$row['notes']}</td>
                            <td>{$row['recieve_by']}</td>
                            <td>{$row['recieve_at']}</td>
                            <td><a href='assign_appointment.php?co_id={$row['co_id']}' class='assign-btn'>Assign</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No case orders found</td></tr>";
            }

            $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.getElementById('dark-mode-toggle').addEventListener('click', () => {
      document.body.classList.toggle('dark');
    });
  </script>
</body>

</html>