    <!-- need to remove -->
    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link dashboard_route">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
        </a>
    </li>

    <li class="nav-item">
        <p style="color: #C2C7D0; font-size: 15px; padding-top:10px">&nbsp;Master</p>
    </li>

    <li class="nav-item">
        <a href="{{ route('company') }}" class="nav-link">
        <i class="fas fa-table"></i>
            <p>&nbsp;Comapny</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('employee') }}" class="nav-link">
            <i class="fas fa-copy"></i>
            <p>&nbsp;Employee</p>
        </a>
    </li>

    <!--user Section -->
    <li class="nav-item">
        <p style="color: #C2C7D0; font-size: 15px; padding-top:10px">&nbsp;User Management</p>
    </li>
    <li class="nav-item">
        <a href="{{ route('role') }}" class="nav-link role_route">
            <i class="fas fa-user-tie"></i>
            <p>&nbsp;Role</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('user') }}" class="nav-link user_route">
        <i class="fas fa-users"></i>
            <p>&nbsp;User</p>
        </a>
    </li>

