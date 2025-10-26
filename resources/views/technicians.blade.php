<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <title>Technician Information</title>
  <script src="https://cdn.tailwindcss.com"></script>
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

    /* Active menu item */
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

  <div class="sidebar">
    <a href="{{ url('dashboard') }}" class="menu-link">🏠 Dashboard</a>
    <a href="{{ route('billing') }}" class="menu-link">💳 Billing</a>
    <a href="{{ url('appointments') }}" class="menu-link">📅 Appointments</a>
    <a href="{{ url('case-orders') }}" class="menu-link">📦 Case Orders</a>
    <a href="{{ url('schedules') }}" class="menu-link">🗓 Schedules</a>
    <a href="{{ url('deliveries') }}" class="menu-link">🚚 Deliveries</a>
    <a href="{{ url('invoices') }}" class="menu-link">🧾 Invoices</a>
    <a href="{{ url('departments') }}" class="menu-link">🏢 Departments</a>
    <a href="{{ route('riders') }}" class="menu-link">🚴 Riders</a>
    <a href="{{ route('clinics') }}" class="menu-link">🏥 Clinics</a>

  </div>

  <!-- Content -->
  <div class="content">
    <h1>Technician Information</h1>

    <div class="flex gap-6">
      <!-- Technician Profile -->
      <div class="w-1/3">
        <div class="bg-white p-4 rounded-lg shadow-lg">
          <h2 class="text-xl font-bold mb-3">Technician Profile</h2>
          <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Technician Photo"
            class="rounded-full w-32 h-32 mb-4 mx-auto" />
          <div class="text-center">
            <p class="text-lg font-semibold">John Reyes</p>
            <p class="text-gray-500">Laboratory Technician</p>
            <p class="text-sm text-gray-400">Phone: +63 923 456 7890</p>
          </div>
        </div>
      </div>

      <!-- Technician Details -->
      <div class="w-2/3">
        <div class="bg-white p-4 rounded-lg shadow-lg">
          <h2 class="text-xl font-bold mb-3">Technician Details</h2>
          <table class="w-full">
            <thead>
              <tr>
                <th class="text-left">Field</th>
                <th class="text-left">Information</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="font-semibold">Age</td>
                <td>35</td>
              </tr>
              <tr>
                <td class="font-semibold">Birthday</td>
                <td>1988-09-20</td>
              </tr>
              <tr>
                <td class="font-semibold">Nickname</td>
                <td>Johnny</td>
              </tr>
              <tr>
                <td class="font-semibold">Email</td>
                <td>john.reyes@dental.com</td>
              </tr>
              <tr>
                <td class="font-semibold">Department</td>
                <td>Denture Plastic</td>
              </tr>
              <tr>
                <td class="font-semibold">Working Hours</td>
                <td>9:00 AM - 5:00 PM</td>
              </tr>
              <tr>
                <td class="font-semibold">Skills</td>
                <td>Orthodontics, Prosthodontics, CAD/CAM</td>
              </tr>
              <tr>
                <td class="font-semibold">Experience</td>
                <td>10 years in the dental laboratory</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Schedules for this Technician -->
    <div class="bg-white p-4 rounded-lg shadow-lg mt-6">
      <h2 class="text-xl font-bold mb-3">Work Schedule</h2>
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left">Date</th>
            <th class="text-left">Shift</th>
            <th class="text-left">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2025-08-02</td>
            <td>8:00 AM - 12:00 PM</td>
            <td class="text-green-600 font-bold">Available</td>
          </tr>
          <tr>
            <td>2025-08-03</td>
            <td>1:00 PM - 5:00 PM</td>
            <td class="text-red-600 font-bold">Busy</td>
          </tr>
          <tr>
            <td>2025-08-04</td>
            <td>9:00 AM - 1:00 PM</td>
            <td class="text-green-600 font-bold">Available</td>
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