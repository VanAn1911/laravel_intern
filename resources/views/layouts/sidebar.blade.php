<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">AdminLTE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @can('is_admin')
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.posts.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Quản lý bài viết</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Quản lý user</p>
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('news.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>Tin tức</p>
                    </a>
                </li>

                @can('is_user')
                <li class="nav-item">
                    <a href="{{ route('posts.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Bài viết</p>
                    </a>
                </li>
                @endcan

                {{-- <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Cập nhật hồ sơ</p>
                    </a>
                </li> --}}
            </ul>
        </nav>
    </div>
</aside>
