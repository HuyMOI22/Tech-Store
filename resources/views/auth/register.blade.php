<x-guest-layout>
    <h4 style="font-weight:700; margin-bottom:6px; color:#0f172a">
        Đăng ký tài khoản
    </h4>
    <p style="color:#64748b; font-size:14px; margin-bottom:24px">
        Tham gia HUYMOI Store ngay hôm nay!
    </p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Họ tên --}}
        <div style="margin-bottom:16px">
            <label class="auth-label">Họ và tên</label>
            <input id="name"
                   type="text"
                   name="name"
                   class="auth-input"
                   value="{{ old('name') }}"
                   placeholder="Nguyễn Văn A"
                   required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
        </div>

        {{-- Email --}}
        <div style="margin-bottom:16px">
            <label class="auth-label">Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   class="auth-input"
                   value="{{ old('email') }}"
                   placeholder="example@gmail.com"
                   required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        {{-- Mật khẩu --}}
        <div style="margin-bottom:16px">
            <label class="auth-label">Mật khẩu</label>
            <input id="password"
                   type="password"
                   name="password"
                   class="auth-input"
                   placeholder="Tối thiểu 8 ký tự"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        {{-- Xác nhận mật khẩu --}}
        <div style="margin-bottom:24px">
            <label class="auth-label">Xác nhận mật khẩu</label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   class="auth-input"
                   placeholder="Nhập lại mật khẩu"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
        </div>

        {{-- Submit --}}
        <button type="submit" class="auth-btn w-full"
                style="width:100%; text-align:center">
            Tạo tài khoản
        </button>

        {{-- Link đăng nhập --}}
        <div style="text-align:center; margin-top:24px; padding-top:20px;
                    border-top:1px solid #e2e8f0">
            <span style="font-size:14px; color:#64748b">
                Đã có tài khoản?
            </span>
            <a href="{{ route('login') }}"
               style="font-size:14px; color:#0ea5e9;
                      font-weight:600; text-decoration:none; margin-left:4px">
                Đăng nhập
            </a>
        </div>
    </form>
</x-guest-layout>