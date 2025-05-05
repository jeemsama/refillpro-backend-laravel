<!-- resources/views/admin/layout.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (Add before closing </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>

<div class="row g-0">
    <div class="col-2 sidebar">
        <h4 class="text-white text-center">RefillPro Admin</h4>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.requests') }}">
            Requests
            <span id="request-notification" class="badge bg-danger" style="display: none;"></span>
        </a>
        <a href="{{ route('admin.approved_shops') }}">Approved Shops</a>





        <a href="{{ route('admin.profile') }}">Profile</a>
        <form action="{{ route('logout') }}" method="POST" class="mt-3 text-center">
            @csrf
            <button class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>
    <div class="col-10 p-4">
        @yield('content')
    </div>
</div>

<script>
    function fetchRequestNotification() {
        fetch("{{ route('admin.pendingRequestCount') }}")
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('request-notification');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            });
    }

    // Call every 10 seconds
    setInterval(fetchRequestNotification, 10000); // 10,000 ms = 10 seconds
    fetchRequestNotification(); // Initial call
</script>


</body>
</html>
