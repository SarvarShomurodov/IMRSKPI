@extends('layouts.app')

@section('content')
<script>
  document.addEventListener("DOMContentLoaded", function () {
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");
      const rememberCheckbox = document.getElementById("remember_me");

      // Oldindan saqlangan login va parolni yuklash
      if (localStorage.getItem("remember") === "true") {
          emailInput.value = localStorage.getItem("email") || "";
          passwordInput.value = localStorage.getItem("password") || "";
          rememberCheckbox.checked = true;
      }

      // Forma yuborilganda loginni saqlashni so‘rash (faqat bir marta)
      document.querySelector("form").addEventListener("submit", function (event) {
          if (rememberCheckbox.checked) {
              if (!localStorage.getItem("asked")) { 
                  event.preventDefault(); // Formani vaqtincha to‘xtatamiz

                  if (confirm("Login va parolni saqlashni xohlaysizmi?")) {
                      localStorage.setItem("email", emailInput.value);
                      localStorage.setItem("password", passwordInput.value);
                      localStorage.setItem("remember", "true");
                  } else {
                      localStorage.removeItem("email");
                      localStorage.removeItem("password");
                      localStorage.removeItem("remember");
                  }
                  
                  localStorage.setItem("asked", "true"); // Faqat bir marta so‘rash uchun belgi qo‘yiladi
                  this.submit(); // Formani qayta yuborish
              }
          }
      });
  });
</script>


  
<!-- Login 13 - Bootstrap Brain Component -->
<section class="py-3 py-md-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
          <div class="card border border-light-subtle rounded-3 shadow-sm">
            <div class="card-body p-3 p-md-4 p-xl-5">
              <div class="text-center mb-3">
                <a href="#!">
                  <img src="{{ asset('admin/images/screen.png') }}" alt="" width="175" height="75">
                </a>
              </div>
              <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Ҳисобингизга кириш</h2>
              <form method="POST" action="{{ route('login') }}">
                @csrf
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <b>Логин ёки Парол хато</b>
                      @endforeach
                    </ul>
                  </div>
                @endif
                <div class="row gy-2 overflow-hidden">
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                      <label for="email" class="form-label">Емаил</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                      <label for="password" class="form-label">Парол</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex gap-2 justify-content-between">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                        <label class="form-check-label text-secondary" for="rememberMe">
                            Логинни сақлаш
                        </label>
                      </div>
                      {{-- <a href="#!" class="link-primary text-decoration-none">Forgot password?</a> --}}
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-grid my-3">
                      <button class="btn btn-primary btn-lg" type="submit">Кириш</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="rememberModal" class="modal fade" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Eslatma</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <p>Логин ва паролни браузерда сақлашni xohlaysizmi?</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yo‘q</button>
                  <button type="button" class="btn btn-primary" id="confirmRemember">Ha</button>
              </div>
          </div>
      </div>
  </div>
  </section>
@endsection
