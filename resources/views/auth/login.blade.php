<x-guest-layout>
    <h4 style="font-weight:700; margin-bottom:6px; color:#0f172a">
        Đăng nhập
    </h4>
    <p style="color:#64748b; font-size:14px; margin-bottom:24px">
        Chào mừng trở lại HUYMOI Store!
    </p>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    @if(session('error'))
    <div style="background:#fee2e2; color:#dc2626; padding:10px 14px;
                border-radius:8px; font-size:14px; margin-bottom:16px">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div style="margin-bottom:16px">
            <label class="auth-label">Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   class="auth-input"
                   value="{{ old('email') }}"
                   placeholder="example@gmail.com"
                   required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        {{-- Password --}}
        <div style="margin-bottom:16px">
            <label class="auth-label">Mật khẩu</label>
            <input id="password"
                   type="password"
                   name="password"
                   class="auth-input"
                   placeholder="••••••••"
                   required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        {{-- Remember Me --}}
        <div style="margin-bottom:20px">
            <label style="display:flex; align-items:center; gap:8px;
                          font-size:14px; color:#374151; cursor:pointer">
                <input type="checkbox" name="remember"
                       style="width:16px; height:16px; accent-color:#0ea5e9">
                Ghi nhớ đăng nhập
            </label>
        </div>

        {{-- Submit --}}
        <div style="display:flex; align-items:center; justify-content:space-between">
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               style="font-size:13px; color:#0ea5e9; text-decoration:none">
                Quên mật khẩu?
            </a>
            @endif
            <button type="submit" class="auth-btn">
                Đăng nhập
            </button>
        </div>

        {{-- Link đăng ký --}}
        <div style="text-align:center; margin-top:24px; padding-top:20px;
                    border-top:1px solid #e2e8f0">
            <span style="font-size:14px; color:#64748b">
                Chưa có tài khoản?
            </span>
            <a href="{{ route('register') }}"
               style="font-size:14px; color:#0ea5e9;
                      font-weight:600; text-decoration:none; margin-left:4px">
                Đăng ký ngay
            </a>
        </div>
    </form>
</x-guest-layout>