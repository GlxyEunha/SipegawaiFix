<div id="sidebar" class="h-screen w-64 bg-gray-800 text-white flex flex-col transition-all duration-300">
    <!-- Logo Section with Toggle Button -->
    <div class="flex items-center justify-between h-16 bg-gray-900 px-4">
        <div class="flex items-center gap-2">
            <img src="/beacukai.png" alt="Logo" class="w-10 h-10" id="system-logo">
            <span class="text-2xl font-bold menu-text">SIPEGAWAI</span>
        </div>
        <button id="toggle-btn" class="text-gray-300 hover:text-white">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    <!-- Navigation Links -->
    <div class="flex-1">
        <ul>
            <li class="hover:bg-gray-700">
                <a href="/home" class="flex items-center gap-4 px-4 py-2 text-gray-300 hover:text-white">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Home</span>
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="/dashboard" class="flex items-center gap-4 px-4 py-2 text-gray-300 hover:text-white">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="/orders" class="flex items-center gap-4 px-4 py-2 text-gray-300 hover:text-white">
                    <i class="fas fa-list"></i>
                    <span class="menu-text">Orders</span>
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="/products" class="flex items-center gap-4 px-4 py-2 text-gray-300 hover:text-white">
                    <i class="fas fa-th"></i>
                    <span class="menu-text">Products</span>
                </a>
            </li>
            <li class="hover:bg-gray-700">
                <a href="/customers" class="flex items-center gap-4 px-4 py-2 text-gray-300 hover:text-white">
                    <i class="fas fa-user"></i>
                    <span class="menu-text">Customers</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Footer Section -->
    <div class="flex items-center justify-between p-4 border-t border-gray-700">
        <div class="flex items-center gap-2">
            <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
            <span class="menu-text text-gray-300">mdo</span>
        </div>
        <i class="fas fa-chevron-down text-gray-400"></i>
    </div>
</div>

<script>
    document.getElementById("toggle-btn").addEventListener("click", function () {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("collapsed");
    });
</script>
