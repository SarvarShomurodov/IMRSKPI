@extends('layouts.client')

@section('content')

{{-- Umumiy statistikalar blok --}}
<div class="kpi-container">
    <div class="kpi-select-text" style="color: rgb(0, 89, 255)">
         <strong>BARCHA YIG'ILGAN KPI BALLARI ({{ number_format($totalSum, 2, '.', '') }})</strong>
    </div>
</div>

{{-- Barcha tasklar bo‘yicha umumiy table --}}
<div class="table-container mb-5">
    <h4>Barcha topshiriqlar</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Ball</th>
                <th>Subtask</th>
                <th>Task</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @foreach($taskStats as $taskName => $data)
                @foreach($data['assignments'] as $assignment)
                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>{{ $assignment->comment ?? 'Comment yo‘q' }}</td>
                        <td>{{ $assignment->addDate }}</td>
                        <td>{{ $assignment->rating }}</td>
                        <td>{{ $assignment->subtask->title ?? 'Nomaʼlum' }}</td>
                        <td>{{ $taskName }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>


{{-- Har bir taskName bo‘yicha --}}
@foreach($taskStats as $taskName => $data)
    @php
        $uniqueId = Str::slug($taskName) . '_' . uniqid();
    @endphp
    
    <div class="kpi-container" onclick="toggleDropdown('{{ $uniqueId }}')">
        <div class="kpi-select-text">
             {{ $taskName }}  ({{ number_format($data['sum'], 2, '.', '') }})
        </div>
        <div class="kpi-select-icon" id="arrowIcon_{{ $uniqueId }}">
            <svg width="16" height="16" viewBox="0 0 24 24">
                <path d="M7 10l5 5 5-5H7z"/>
            </svg>
        </div>
    </div>
    
    <!-- Dropdown -->
    <div class="kpi-dropdown mb-3" id="kpiDropdown_{{ $uniqueId }}" style="display: none;">
        <!-- Switch -->
        <div class="switch-wrapper">
            <label class="switch">
                <input type="checkbox" id="tableToggle_{{ $uniqueId }}" onchange="toggleTable('{{ $uniqueId }}')">
                <span class="slider"></span>
            </label>
            <span class="switch-label">Data sifatida ko‘rish</span> 
        </div>
    
        <!-- Card view -->
        <div class="card-container" id="cardView_{{ $uniqueId }}">
            @foreach($data['assignments'] as $assignment)
            <div class="card mt-3">
                <div class="section">
                    <div class="block">
                        <span class="icon">📁</span>
                        <div>
                            <div class="label">Task</div>
                            <div class="value">{{ $taskName }}</div>
                        </div>
                    </div>
                    <div class="block">
                        <span class="icon">🖥️</span>
                        <div>
                            <div class="label">Subtask</div>
                            <div class="value">{{ $assignment->subtask->title ?? 'Nomaʼlum' }}</div>
                        </div>
                    </div>
                    <div class="block">
                        <span class="icon">⭐</span>
                        <div>
                            <div class="label">Ball</div>
                            <div class="rating">{{ $assignment->rating }}</div>
                        </div>
                    </div>
                    <div class="block">
                        <span class="icon">📅</span>
                        <div>
                            <div class="label">Sana</div>
                            <div class="date">{{ $assignment->addDate }}</div>
                        </div>
                    </div>
                    <div class="block" style="flex: 1 1 100%">
                        <span class="icon">📝</span>
                        <div class="izoh">{{ $assignment->comment ?? 'Comment yo‘q' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    
        <!-- Table view -->
        <div class="table-container" id="dataTable_{{ $uniqueId }}" style="display: none;">
            <table id="myTable_{{ $uniqueId }}">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subtask</th>
                        <th>Date</th>
                        <th>Ball</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['assignments'] as $index => $assignment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $assignment->subtask->title ?? 'Nomaʼlum' }}</td>
                            <td>{{ $assignment->addDate }}</td>
                            <td>{{ number_format($assignment->rating, 2, '.', '') }}</td>
                            <td>{{ $assignment->comment ?? 'Comment yo‘q' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

{{-- JavaScript --}}
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById('kpiDropdown_' + id);
        const arrow = document.getElementById('arrowIcon_' + id).querySelector('svg');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        arrow.classList.toggle('rotate');
    }

    function toggleTable(id) {
        const isChecked = document.getElementById('tableToggle_' + id).checked;
        document.getElementById('dataTable_' + id).style.display = isChecked ? 'block' : 'none';
        document.getElementById('cardView_' + id).style.display = isChecked ? 'none' : 'block';
    }
</script>
@endsection
