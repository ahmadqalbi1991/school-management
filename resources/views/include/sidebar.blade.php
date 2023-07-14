<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{route('dashboard')}}">
            <div class="logo-img">
                <img height="30" src="{{ !empty(getSchoolSettings()->logo) ? asset(getSchoolSettings()->logo) : asset('img/logo_white.png')}}" class="header-brand-img" title="RADMIN">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
    @endphp

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}">
                        <i class="ik ik-bar-chart-2"></i>
                        <span>{{ __('Dashboard')}}</span>
                    </a>
                </div>
                @if(in_array(\Auth::user()->role, ['admin', 'teacher']))
                    @can('manage_formative_assessments')
                        <div class="nav-item {{ ($segment1 == 'formative-assessments') ? 'active' : '' }}">
                            <a href="{{route('formative-assessments.index')}}">
                                <i class="fas fa-copy"></i>
                                <span>{{ __('Formative Assessment')}}</span>
                            </a>
                        </div>
                        <div class="nav-item {{ ($segment1 == 'reports') ? 'active' : '' }}">
                            <a href="{{route('reports.index')}}">
                                <i class="fas fa-chart-bar"></i>
                                <span>{{ __('Formative Reports')}}</span>
                            </a>
                        </div>
                    @endcan
                @endif
                @can('manage_user')
                    <div
                        class="nav-item {{ ($segment1 == 'users' || $segment1 == 'roles'||$segment1 == 'permission' ||$segment1 == 'user') ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user"></i><span>{{ __('Administrator')}}</span></a>
                        <div class="submenu-content">
                            <!-- only those have manage_user permission will get access -->
                            @can('manage_admins')
                                <a href="{{url('users')}}"
                                   class="menu-item {{ ($segment1 == 'users') ? 'active' : '' }}">{{ __('Admins')}}</a>
                                <a href="{{url('user/create')}}"
                                   class="menu-item {{ ($segment1 == 'user' && $segment2 == 'create') ? 'active' : '' }}">{{ __('Add User')}}</a>
                            @endcan
                            <!-- only those have manage_role permission will get access -->
                            @can('manage_roles')
                                <a href="{{url('roles')}}"
                                   class="menu-item {{ ($segment1 == 'roles') ? 'active' : '' }}">{{ __('Roles')}}</a>
                            @endcan
                            <!-- only those have manage_permission permission will get access -->
                            @can('manage_permission')
                                <a href="{{url('permission')}}"
                                   class="menu-item {{ ($segment1 == 'permission') ? 'active' : '' }}">{{ __('Permission')}}</a>
                            @endcan
                        </div>
                    </div>
                @endcan
                @can('manage_teachers')
                    <div class="nav-item {{ ($segment1 == 'learners-subjects') ? 'active' : '' }}">
                        <a href="{{route('learners-subjects.index')}}">
                            <i class="ik ik-book"></i>
                            <span>{{ __('Students Subjects')}}</span>
                        </a>
                    </div>
                @endcan
                @can('manage_teachers')
                    <div
                        class="nav-item {{ ($segment1 == 'teachers') ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user"></i><span>{{ __('Teachers')}}</span></a>
                        <div class="submenu-content">
                            <!-- only those have manage_user permission will get access -->
                            @can('manage_teachers')
                                <a href="{{ route('teachers.index') }}"
                                   class="menu-item {{ ($segment1 == 'teachers') ? 'active' : '' }}">{{ __('Teachers List')}}</a>
                            @endcan
                        </div>
                    </div>
                @endcan
                @can('manage_learners')
                    <div class="nav-item {{ ($segment1 == 'learners') ? 'active' : '' }}">
                        <a href="{{route('learners.index')}}">
                            <i class="ik ik-user"></i>
                            <span>{{ __('Learners')}}</span>
                        </a>
                    </div>
                @endcan
                @can('manage_subjects')
                    <div
                        class="nav-item {{ ($segment1 == 'performance-levels' || $segment1 == 'subjects' || $segment1 == 'strands' || $segment1 == 'sub-strands' || $segment1 == 'learning-activities' || $segment1 == 'terms' || $segment1 == 'term-subjects') ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-book-open"></i><span>{{ __('Subjects')}}</span></a>
                        <div class="submenu-content">
                            <!-- only those have manage_user permission will get access -->
                            @can('manage_subjects')
                                <a href="{{ route('subjects.index') }}"
                                   class="menu-item {{ ($segment1 == 'subjects') ? 'active' : '' }}">{{ __('Subjects List')}}</a>
                            @endcan
                            @can('manage_strands')
                                <a href="{{ route('strands.index') }}"
                                   class="menu-item {{ ($segment1 == 'strands') ? 'active' : '' }}">{{ __('Strands')}}</a>
                                <a href="{{ route('sub-strands.index') }}"
                                   class="menu-item {{ ($segment1 == 'sub-strands') ? 'active' : '' }}">{{ __('Sub Strands')}}</a>
                                <a href="{{ route('learning-activities.index') }}"
                                   class="menu-item {{ ($segment1 == 'learning-activities') ? 'active' : '' }}">{{ __('Learning Activities')}}</a>
                            @endcan
                            @can('manage_terms')
                                <a href="{{ route('terms.index') }}"
                                   class="menu-item {{ ($segment1 == 'terms') ? 'active' : '' }}">{{ __('Terms')}}</a>
                                <a href="{{ route('term-subjects.index') }}"
                                   class="menu-item {{ ($segment1 == 'term-subjects') ? 'active' : '' }}">{{ __('Term Subjects')}}</a>
                            @endcan
                            @can('manage_performance_levels')
                                <a href="{{ route('performance-levels.index') }}"
                                   class="menu-item {{ ($segment1 == 'performance-levels') ? 'active' : '' }}">{{ __('Performance Levels')}}</a>
                            @endcan
                        </div>
                    </div>
                @endcan
                @can('manage_settings')
                    <div class="nav-item {{ ($segment1 == 'settings') ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-settings"></i><span>{{ __('Settings')}}</span></a>
                        <div class="submenu-content">
                            <!-- only those have manage_user permission will get access -->
                            <a href="{{ route('settings.schools.index') }}"
                               class="menu-item {{ ($segment2 == 'schools') ? 'active' : '' }}">{{ __('Schools')}}</a>
                        </div>
                    </div>
                @endcan
                @can('manage_classes')
                    <div class="nav-item {{ ($segment1 == 'classes') ? 'active' : '' }}">
                        <a href="{{route('classes.index')}}">
                            <i class="ik ik-home"></i>
                            <span>{{ __('Classes')}}</span>
                        </a>
                    </div>
                @endcan
                @can('manage_streams')
                    <div class="nav-item {{ ($segment1 == 'streams') ? 'active' : '' }}">
                        <a href="{{route('streams.index')}}">
                            <i class="ik ik-list"></i>
                            <span>{{ __('Streams')}}</span>
                        </a>
                    </div>
                @endcan
            </nav>
        </div>
    </div>
</div>
