<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Xodimlar KPI natijalari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    
    <!-- JSZip (Excel export uchun) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    
    <!-- pdfmake (PDF export uchun) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            @include('layouts.inc.client.sidebar')
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 content">
                <!-- Top Menu -->
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('client.index') }}"
                    class="btn btn-outline-light {{ request()->routeIs('client.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i> DATASET
                 </a>
                 
                 <a href="{{ route('client.subtask') }}"
                    class="btn btn-outline-light {{ request()->routeIs('client.subtask') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i> ALL DATA FOR EACH SUBTASK
                 </a> 
                 <a href="{{ route('client.allsubtask') }}"
                    class="btn btn-outline-light {{ request()->routeIs('client.allsubtask') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> REYTING
                 </a>                   
                 
                 {{-- <a href="#" class="btn btn-outline-light"></a> --}}
                 <a href="{{ route('profile.edit') }}"
                    class="btn btn-outline-light {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i> PROFIL MA'LUMOTLARI
                 </a> 
                    {{-- <a href="{{ url('s') }}" class="btn btn-outline-light">PROFIL MA'LUMOTLARI</a> --}}
                </div>
                <hr />
                @yield('content')
                @yield('scripts')
            </main>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
     $(document).ready(function() {
    $('table[id^="myTable_"]').each(function() {
        $(this).DataTable({
            ordering: true,
            order: [[0, 'asc']],
            paging: false,
            lengthChange: false,
            language: {
                search: "Qidiruv:",
                zeroRecords: "Hech qanday mos yozuv topilmadi",
            },
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'print'],
            info: false
        });
    });
});
    </script>
</body>

</html>
        