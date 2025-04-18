@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if(auth()->user() && auth()->user()->hasRole('Super Admin'))
                    <div class="card-header"><a href="https://t.me/SARVAR0297">Adminga murojat qiling </a>yoki http://kpi.imrs.uz/admin/users ga o'ting</div>
                @elseif (auth()->user() && auth()->user()->hasRole('Admin'))
                    <div class="card-header"><a href="https://t.me/SARVAR0297">Adminga murojat qiling </a>yoki http://kpi.imrs.uz/grafik ga o'ting</div>
                @else
                    <div class="card-header"><a href="https://t.me/SARVAR0297">Adminga murojat qiling </a>yoki http://kpi.imrs.uz/index ga o'ting</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection