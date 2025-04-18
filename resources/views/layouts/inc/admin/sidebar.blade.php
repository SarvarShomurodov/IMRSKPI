<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="yon-panel">
      @if(auth()->user() && auth()->user()->hasRole('Super Admin'))
        <nav class="nav flex-column">
        <div class="bolim-sarlavhasi">Akkauntlar</div>
          @canany(['create-user', 'edit-user', 'delete-user'])
            <a class="menyu-link {{ request()->routeIs('admin.users*') ? 'faol-link' : '' }}" href="{{ route('admin.users.index') }}">
              <i class="menyu-ikon fas fa-user"></i>
                Xodimlar ro'yxati
            </a>
          @endcanany 
          @canany(['create-project', 'edit-project', 'delete-project'])
            <a class="menyu-link {{ request()->routeIs('admin.projects*') ? 'faol-link' : '' }}" href="{{ route('admin.projects.index') }}">
              <i class="menyu-ikon fas fa-folder-open"></i>
              Loyihalar
            </a>
          @endcanany
          <div class="bolim-sarlavhasi mt-4">Avtorizatsiya</div>
          @canany(['create-role', 'edit-role', 'delete-role'])
            <a class="menyu-link {{ request()->routeIs('admin.roles*') ? 'faol-link' : '' }}" href="{{ route('admin.roles.index') }}">
              <i class="menyu-ikon fas fa-key"></i>
                Rollar
            </a>
          @endcanany
          <div class="bolim-sarlavhasi mt-4">Tasklar</div>
          @canany(['create-task', 'edit-task', 'delete-task'])
            <a class="menyu-link {{ request()->routeIs('admin.tasks*') ? 'faol-link' : '' }}" href="{{ route('admin.tasks.index') }}">
                <i class="menyu-ikon fas fa-tasks"></i>
                <span class="menu-title">Tasks</span>
            </a>
          @endcanany
          @canany(['create-subtask', 'edit-subtask', 'delete-subtask'])
            <a class="menyu-link {{ request()->routeIs('admin.subtasks*') ? 'faol-link' : '' }}" href="{{ route('admin.subtasks.index') }}">
              <i class="menyu-ikon fas fa-list-ul"></i>
              Sub Tasks
            </a>
          @endcanany
          @canany(['create-subtask', 'edit-subtask', 'delete-subtask'])
            <a class="menyu-link {{ request()->routeIs('admin.task_assignments*') ? 'faol-link' : '' }}" href="{{ route('admin.task_assignments.index') }}">
              <i class="menyu-ikon fas fa-users"></i>
              Task Assignees
            </a>
          @endcanany
        </nav>
      @endif
      {{-- Admin --}}
      @if(auth()->user() && auth()->user()->hasRole('Admin'))
        <nav class="nav flex-column">
          <div class="bolim-sarlavhasi">Sahifalar</div>
          <a class="menyu-link {{ request()->routeIs('grafik') ? 'faol-link' : '' }}" href="{{ route('grafik') }}">
            <i class="fas fa-chart-line menyu-ikon"></i>
            Asosiy sahifa
          </a>
          <a class="menyu-link {{ request()->routeIs(['client-task.task-details','task.swod','client-task.show']) ? 'faol-link' : '' }}" href="{{ route('task.swod') }}">
            <i class="fas fa-chart-pie menyu-ikon"></i>
            SWOD tahlil
          </a>
          @canany(['create-task', 'edit-task', 'delete-task'])
            <a class="menyu-link {{ Request::is('tasks') || Request::is('tasks/*') ? 'faol-link' : '' }}" href="{{ route('tasks.index') }}">
              <i class="fas fa-clipboard-check menyu-ikon"></i>
              KPI baholash
            </a>
          @endcanany
          <a class="menyu-link {{ request()->routeIs(['accounts.staffs','accounts.staff.kpi']) ? 'faol-link' : '' }}" href="{{ route('accounts.staffs') }}">
            <i class="fas fa-users menyu-ikon"></i>
            Xodimlar ro'yxati
          </a>
          <div class="bolim-sarlavhasi mt-3">Yordam</div>
          <a class="menyu-link" href="https://t.me/SARVAR0297 ">
            <i class="fab fa-telegram-plane menyu-ikon"></i>
            Qo'llab-quvvatlash
          </a>
        </nav>
      @endif
    </div>
</nav>
