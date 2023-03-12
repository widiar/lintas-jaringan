<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Messages Dropdown Menu -->
        @role('admin|super admin')
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">{{ count(getTicketAdmin()) }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @foreach (getTicketAdmin() as $tiket)
                <a href="{{ route('ticket.edit', encodehasids($tiket->id)) }}" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{ $tiket->subject }}
                            </h3>
                            <p class="text-sm">{{ $tiket->detail[0]->body }}</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                @endforeach
                <div class="dropdown-divider"></div>
                <a href="{{ route('ticket') }}" class="dropdown-item dropdown-footer">Lihat Semua Tiket</a>
            </div>
        </li>
        @endrole

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->