@extends('layouts.client') {{-- yoki layouts.client --}}

@section('content')
<style>
    .form-section-wrapper,
.user-profile-box {
  flex: 1 1 45%;
  padding: 20px;
  box-sizing: border-box;
}

h6 {
  padding: 1%;
  background-color: #003b46;
  margin-bottom: 20px;
  border-radius: 5px;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #ffffff;
  font-size: 15px;
  letter-spacing: 0.6px;
  /* background-color: #003b46; */
  padding: 6px 10px;
  border-radius: 5px;
}

input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px;
  margin-bottom: 20px;
  background-color: #4a5b5f;
  border: 1px solid #66cccc;
  border-radius: 6px;
  color: white;
  font-size: 14px;
}

button {
  padding: 14px 28px;
  background: linear-gradient(135deg, #004d4d, #007777);
  border: none;
  border-radius: 8px;
  color: #ffffff;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  transition: all 0.3s ease;
}

button:hover {
  background: linear-gradient(135deg, #006666, #00aaaa);
  transform: translateY(-2px);
}

.detailed-profile-info p {
  margin: 10px 0;
}

.highlighted-project-info {
  background-color: #145c4b;
  padding: 12px;
  margin-top: 20px;
  display: flex;
  align-items: center;
  border-radius: 6px;
}

.highlighted-project-info span {
  margin-left: 10px;
}

.icon-style-indicator {
  margin-right: 10px;
}
</style>
<div class="row">
    <div class="col-md-6"><div class="form-section-wrapper">
        <h6>Parolni yangilash</h6>
            
        <!-- Success or error message display -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            <label>Email manzilingizni kiriting:</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
        
            <label>Eski parolingizni kiriting:</label>
            <input type="password" name="old_password">
        
            <label>Yangi parolingizni kiriting:</label>
            <input type="password" name="new_password">
        
            <label>Yangi parolni tasdiqlang:</label>
            <input type="password" name="new_password_confirmation">
        
            <button type="submit">	<i class="fas fa-lock-open"></i> Parolni yangilash</button>
        </form>
      </div></div>
    <div class="col-md-6">
        <div class="user-profile-box">
          <h6>User Profile</h6>
          <div class="detailed-profile-info">
            <p><strong>🔹 User Email:</strong> {{ auth()->user()->email }}</p>
            <p><strong>🔹 Full Name:</strong> {{ auth()->user()->lastName }} {{ auth()->user()->firstName }}</p>
            <p><strong>🏢 Position:</strong> {{ auth()->user()->position }}</p>
          </div>
          <div class="highlighted-project-info">
            <span>📁</span>
            <span>Current Project: {{ auth()->user()->project->name ?? 'Loyiha biriktirilmagan' }}</span>
          </div>
        </div></div>
</div>

@endsection
