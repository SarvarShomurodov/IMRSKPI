<nav class="col-md-2 d-none d-md-block sidebar">
    <div class="position-sticky">
        <img class="mt-3" style="width: 45%" src="{{ asset('admin/images/screen.png') }}" alt="logo" />
        <h2 class="mt-5 mb-3">Xodimlar uchun</h2>
        <p class="text-white">
            <strong><i class="fas fa-user"></i> Xodim emaili: {{ auth()->user()->email  }}</strong><br>
            <strong><i class="fas fa-id-badge"></i> Xodim IDsi: {{ auth()->user()->id  }}</strong>
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="btn btn-primary  mb-5" style="color: white"  type="submit" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="fas fa-sign-out-alt"></i> {{ __('Chiqish') }}
            </a>
        </form>
        <hr>
        <h2 class="mt-3 mb-3">Vaqt bo'yicha filter</h2>
        @php
            $routes = [
                'client.index' => route('client.index'),
                'client.subtask' => route('client.subtask'),
                'client.allsubtask' => route('client.allsubtask'),
            ];
        @endphp
        <form action="{{ $routes[Route::currentRouteName()] ?? '#' }}" method="GET">
            <div class="mb-3">
                <label for="yearSelect" class="form-label"><i class="fas fa-calendar-alt"></i> Yilni tanlang:</label>
                <select class="form-select" id="yearSelect" name="year">
                    <option value="">Barcha yillar</option> 
                    <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                    <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                    <option value="2023" {{ request('year') == '2023' ? 'selected' : '' }}>2023</option>
                </select>
            </div>
        
            <div class="mb-3">
                <label for="monthSelect" class="form-label"><i class="fas fa-calendar-day"></i> Oyni tanlang:</label>
                <select class="form-select" id="monthSelect" name="month">
                    <option value="">Barcha oylar</option> 
                    @foreach ([
                        '01' => 'Yanvar', '02' => 'Fevral', '03' => 'Mart', '04' => 'Aprel',
                        '05' => 'May', '06' => 'Iyun', '07' => 'Iyul', '08' => 'Avgust',
                        '09' => 'Sentabr', '10' => 'Oktabr', '11' => 'Noyabr', '12' => 'Dekabr'
                    ] as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrlash</button>
        </form>
    </div>
</nav>