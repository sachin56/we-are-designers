<aside class="main-sidebar sidebar-dark-primary elevation-6">
    <a href="{{ route('home') }}" class="brand-link">
        <span class="brand-text font-weight-light" style="font-size: 18px;">We Are Designers</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
