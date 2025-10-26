<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Departments - Staff Profiles</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #e7eaf0;
    }

    /* Sidebar styling */
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

    .sidebar-header {
      font-weight: bold;
      font-size: 1.25rem;
      padding: 0 16px 12px;
      color: black;
    }

    /* Content styling */
    .content {
      margin-left: 240px;
      padding: 24px 32px;
      min-height: 100vh;
      background: url('pexels-karolina-grabowska-6627592.jpg') no-repeat center center;
      background-size: cover;
      overflow-y: auto;
    }

    /* Header */
    h1 {
      color: #05445e;
      font-weight: 700;
      font-size: 2.5rem;
      margin-bottom: 2rem;
      text-align: center;
      text-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
    }

    /* Grid */
    .profiles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 2rem;
    }

    /* Flip card container */
    .profile-card {
      width: 100%;
      height: 300px;
      perspective: 1000px;
      cursor: pointer;
    }

    /* Inner container to flip */
    .profile-card-inner {
      position: relative;
      width: 100%;
      height: 100%;
      text-align: center;
      transition: transform 0.6s;
      transform-style: preserve-3d;
      box-shadow: 0 4px 6px rgb(0 0 0 / 0.1);
      border-radius: 0.75rem;
      background: white;
    }

    .profile-card.flipped .profile-card-inner {
      transform: rotateY(180deg);
    }

    /* Front and back sides */
    .profile-card-front,
    .profile-card-back {
      position: absolute;
      width: 100%;
      height: 100%;
      backface-visibility: hidden;
      border-radius: 0.75rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }

    /* Front side */
    .profile-card-front {
      color: #05445e;
    }

    .profile-pic {
      width: 112px;
      height: 112px;
      border-radius: 9999px;
      object-fit: cover;
      margin-bottom: 1rem;
      border: 2px solid #189ab4;
    }

    .profile-name {
      font-weight: 700;
      font-size: 1.125rem;
      margin-bottom: 0.25rem;
    }

    .profile-position {
      color: #189ab4;
      font-weight: 600;
    }

    /* Back side */
    .profile-card-back {
      background: #189ab4;
      color: white;
      transform: rotateY(180deg);
      font-weight: 600;
      font-size: 0.95rem;
      line-height: 1.5;
      text-align: left;
    }

    .profile-card-back h3 {
      margin-bottom: 1rem;
      font-size: 1.25rem;
      font-weight: 700;
      text-align: center;
    }

    .profile-card-back p {
      margin: 0.2rem 0;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->

  </div>
  <div class="p-4">
    <button class="w-full p-2 bg-red-500 text-white rounded">Logout</button>
  </div>
  </div>

  <!-- Content -->
  <main class="content">
    <h1>Departments - Staff Profiles</h1>

    <div class="profiles-grid">
      <!-- Profile 1 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Anna Cruz" class="profile-pic" />
            <div class="profile-name">Anna Cruz</div>
            <div class="profile-position">Dental Assistant</div>
          </div>
          <div class="profile-card-back">
            <h3>Anna Cruz</h3>
            <p><strong>Age:</strong> 28</p>
            <p><strong>Birthday:</strong> 1997-02-15</p>
            <p><strong>Nickname:</strong> Annie</p>
            <p><strong>Contact #:</strong> +63 912 345 6789</p>
          </div>
        </div>
      </div>

      <!-- Profile 2 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Reyes" class="profile-pic" />
            <div class="profile-name">John Reyes</div>
            <div class="profile-position">Laboratory Technician</div>
          </div>
          <div class="profile-card-back">
            <h3>John Reyes</h3>
            <p><strong>Age:</strong> 35</p>
            <p><strong>Birthday:</strong> 1988-09-20</p>
            <p><strong>Nickname:</strong> Johnny</p>
            <p><strong>Contact #:</strong> +63 923 456 7890</p>
          </div>
        </div>
      </div>

      <!-- Profile 3 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Maria Lopez" class="profile-pic" />
            <div class="profile-name">Maria Lopez</div>
            <div class="profile-position">Office Manager</div>
          </div>
          <div class="profile-card-back">
            <h3>Maria Lopez</h3>
            <p><strong>Age:</strong> 30</p>
            <p><strong>Birthday:</strong> 1993-06-11</p>
            <p><strong>Nickname:</strong> Mari</p>
            <p><strong>Contact #:</strong> +63 934 567 8901</p>
          </div>
        </div>
      </div>

      <!-- Profile 4 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/men/48.jpg" alt="Carlos Gomez" class="profile-pic" />
            <div class="profile-name">Carlos Gomez</div>
            <div class="profile-position">Chief Dentist</div>
          </div>
          <div class="profile-card-back">
            <h3>Carlos Gomez</h3>
            <p><strong>Age:</strong> 40</p>
            <p><strong>Birthday:</strong> 1983-03-05</p>
            <p><strong>Nickname:</strong> Carl</p>
            <p><strong>Contact #:</strong> +63 945 678 9012</p>
          </div>
        </div>
      </div>

      <!-- Profile 5 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/women/51.jpg" alt="Sofia Diaz" class="profile-pic" />
            <div class="profile-name">Sofia Diaz</div>
            <div class="profile-position">Receptionist</div>
          </div>
          <div class="profile-card-back">
            <h3>Sofia Diaz</h3>
            <p><strong>Age:</strong> 26</p>
            <p><strong>Birthday:</strong> 1997-11-09</p>
            <p><strong>Nickname:</strong> Sofi</p>
            <p><strong>Contact #:</strong> +63 956 789 0123</p>
          </div>
        </div>
      </div>

      <!-- Profile 6 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Miguel Santos" class="profile-pic" />
            <div class="profile-name">Miguel Santos</div>
            <div class="profile-position">Dental Hygienist</div>
          </div>
          <div class="profile-card-back">
            <h3>Miguel Santos</h3>
            <p><strong>Age:</strong> 31</p>
            <p><strong>Birthday:</strong> 1992-08-23</p>
            <p><strong>Nickname:</strong> Mike</p>
            <p><strong>Contact #:</strong> +63 967 890 1234</p>
          </div>
        </div>
      </div>

      <!-- Profile 7 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Elena Rivera" class="profile-pic" />
            <div class="profile-name">Elena Rivera</div>
            <div class="profile-position">Billing Specialist</div>
          </div>
          <div class="profile-card-back">
            <h3>Elena Rivera</h3>
            <p><strong>Age:</strong> 29</p>
            <p><strong>Birthday:</strong> 1994-12-15</p>
            <p><strong>Nickname:</strong> Elle</p>
            <p><strong>Contact #:</strong> +63 978 901 2345</p>
          </div>
        </div>
      </div>

      <!-- Profile 8 -->
      <div class="profile-card" tabindex="0">
        <div class="profile-card-inner">
          <div class="profile-card-front">
            <img src="https://randomuser.me/api/portraits/men/54.jpg" alt="Daniel Cruz" class="profile-pic" />
            <div class="profile-name">Daniel Cruz</div>
            <div class="profile-position">IT Support</div>
          </div>
          <div class="profile-card-back">
            <h3>Daniel Cruz</h3>
            <p><strong>Age:</strong> 33</p>
            <p><strong>Birthday:</strong> 1990-05-30</p>
            <p><strong>Nickname:</strong> Dan</p>
            <p><strong>Contact #:</strong> +63 989 012 3456</p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Flip cards on click
    document.querySelectorAll('.profile-card').forEach(card => {
      card.addEventListener('click', () => {
        card.classList.toggle('flipped');
      });
    });
  </script>

</body>

</html>