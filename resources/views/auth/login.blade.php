<form method="POST" action="/login">
    @csrf

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif
</form>