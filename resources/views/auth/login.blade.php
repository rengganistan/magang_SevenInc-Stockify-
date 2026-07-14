<!DOCTYPE html>
<html>
<head>
    <title>Stockify Login</title>
</head>
<body>

<h2>Login Stockify</h2>

@if(session('error'))
    <p style="color:red">
        {{ session('error') }}
    </p>
@endif

<form action="{{ route('login.process') }}" method="POST">

    @csrf

    <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="Email">

    @error('email')
        <p style="color:red">{{ $message }}</p>
    @enderror

    <br><br>

    <input
        type="password"
        name="password"
        placeholder="Password">

    @error('password')
        <p style="color:red">{{ $message }}</p>
    @enderror

    <br><br>

    <button type="submit">
        Login
    </button>

</form>

</body>
</html>
