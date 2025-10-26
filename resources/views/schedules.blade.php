<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Schedules - Denture Reports</title>
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
      background-color: rgba(255 255 255 / 0.9);
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
      background-color: transparent !important;
      cursor: default;
    }

    .busy {
      color: #dc2626;
      /* red-600 */
      font-weight: 600;
    }

    .available {
      background-color: #d1fae5;
      /* green-100 */
    }

    .available-status {
      color: #059669;
      /* green-600 */
      font-weight: 600;
    }

    button.assign-btn {
      color: #2563eb;
      /* blue-600 */
      background: none;
      border: none;
      padding: 0;
      font-size: 0.875rem;
      cursor: pointer;
      transition: text-decoration 0.2s;
    }

    button.assign-btn:hover {
      text-decoration: underline;
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
      <a href="{{ url('case-orders') }}" class="menu-link">📦 Case Orders</a>
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
    <h1>Laboratory Dashboard</h1>

    <div class="search-container">
      <input type="text" placeholder="Search appointment..." />
      <button class="search-btn">Search</button>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-lg">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Technician Name</th>
            <th>Department</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Busy technicians -->
          <tr>
            <td>1</td>
            <td>Miguelito Gueriro</td>
            <td>Denture Plastic</td>
            <td>2025-07-29</td>
            <td>1:00 PM - 5:00 PM</td>
            <td class="busy">Busy</td>
            <td><button class="assign-btn" disabled>Assigned</button></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Eddie Garcia</td>
            <td>Denture Plastic</td>
            <td>2025-07-30</td>
            <td>9:00 AM - 1:00 PM</td>
            <td class="busy">Busy</td>
            <td><button class="assign-btn" disabled>Assigned</button></td>
          </tr>
          <tr>
            <td>3</td>
            <td>Junnel Padilla</td>
            <td>Denture Plastic</td>
            <td>2025-07-31</td>
            <td>8:00 AM - 12:00 PM</td>
            <td class="busy">Busy</td>
            <td><button class="assign-btn" disabled>Assigned</button></td>
          </tr>
          <tr>
            <td>4</td>
            <td>Arryan Abalos</td>
            <td>Denture Plastic</td>
            <td>2025-08-01</td>
            <td>1:00 PM - 5:00 PM</td>
            <td class="busy">Busy</td>
            <td><button class="assign-btn" disabled>Assigned</button></td>
          </tr>
          <tr>
            <td>5</td>
            <td>Anna Rose Dela Pena</td>
            <td>Denture Plastic</td>
            <td>2025-08-02</td>
            <td>8:00 AM - 12:00 PM</td>
            <td class="busy">Busy</td>
            <td><button class="assign-btn" disabled>Assigned</button></td>
          </tr>

          <!-- Available technicians -->
          <tr class="available">
            <td>6</td>
            <td>Ben Tin</td>
            <td>—</td>
            <td>—</td>
            <td>—</td>
            <td class="available-status">Available</td>
            <td><button class="assign-btn">Assign</button></td>
          </tr>
          <tr class="available">
            <td>7</td>
            <td>Carl Dela Rosa</td>
            <td>—</td>
            <td>—</td>
            <td>—</td>
            <td class="available-status">Available</td>
            <td><button class="assign-btn">Assign</button></td>
          </tr>
          <tr class="available">
            <td>8</td>
            <td>Diana Roos</td>
            <td>—</td>
            <td>—</td>
            <td>—</td>
            <td class="available-status">Available</td>
            <td><button class="assign-btn">Assign</button></td>
          </tr>
          <tr class="available">
            <td>9</td>
            <td>Ethan Uy</td>
            <td>—</td>
            <td>—</td>
            <td>—</td>
            <td class="available-status">Available</td>
            <td><button class="assign-btn">Assign</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Dark mode toggle (optional)
    document.getElementById('dark-mode-toggle').addEventListener('click', () => {
      document.body.classList.toggle('dark');
    });
  </script>
</body>

</html>