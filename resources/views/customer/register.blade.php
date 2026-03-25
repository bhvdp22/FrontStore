<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Register - FrontStore</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(44,62,80,0.10);
      border: 1px solid #e5e7eb;
      max-width: 400px;
      width: 100%;
      padding: 38px 32px 28px 32px;
      margin: 32px 0;
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    .register-header {
      text-align: center;
      margin-bottom: 8px;
    }
    .register-header h2 {
      font-size: 28px;
      font-weight: 700;
      color: #232f3e;
      margin-bottom: 4px;
      letter-spacing: 0.5px;
    }
    .register-header p {
      font-size: 15px;
      color: #565959;
      margin-bottom: 0;
    }
    .form-group {
      margin-bottom: 18px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    label {
      font-weight: 600;
      font-size: 14px;
      color: #37475a;
      margin-bottom: 2px;
    }
    input {
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
      padding: 13px 16px;
      border: 1.5px solid #e4e8ec;
      border-radius: 12px;
      font-size: 16px;
      background: #f8fafc;
      color: #232f3e;
      box-shadow: 0 2px 8px rgba(44,62,80,0.04);
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }
    input:focus {
      border-color: #febd69;
      box-shadow: 0 4px 16px rgba(247,202,0,0.10);
      background: #fffbe6;
    }
    input:hover {
      border-color: #cbd5e1;
    }
    .btn {
      width: 100%;
      background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
      border: none;
      padding: 13px 0;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 700;
      font-size: 16px;
      color: #232f3e;
      box-shadow: 0 2px 8px rgba(247,202,0,0.10);
      transition: background 0.2s;
      margin-top: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .btn:hover {
      background: linear-gradient(180deg, #f7ca00 0%, #e6b800 100%);
      box-shadow: 0 4px 12px rgba(247,202,0,0.18);
    }
    .error {
      color: #cc0c39;
      font-size: 13px;
      margin-top: 2px;
    }
    .helper {
      font-size: 14px;
      color: #565959;
      text-align: center;
      margin-top: 18px;
    }
    .helper a {
      color: #007185;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s;
    }
    .helper a:hover {
      color: #c7511f;
      text-decoration: underline;
    }
    @media (max-width: 600px) {
      .register-card {
        padding: 22px 8px 18px 8px;
      }
      .register-header h2 {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>
  <div style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:0;pointer-events:none;overflow:hidden;">
    <span style="font-family:'Dancing Script',cursive;font-size:14vw;font-weight:700;color:#232f3e22;user-select:none;letter-spacing:6px;white-space:nowrap;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:100vw;text-align:center;">FrontStore</span>
  </div>
  <div class="register-card" style="position:relative;z-index:1;">
    <div class="register-header">
      <h2><i class="fas fa-user-plus" style="color:#febd69;"></i> Create Account</h2>
      <p>Sign up for your FrontStore account</p>
    </div>
    <form method="POST" action="{{ route('customer.register.submit') }}">
      @csrf
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
        @error('name')<div class="error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        @error('email')<div class="error">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="new-password">
        @error('password')<div class="error">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="btn"><i class="fas fa-user-plus"></i> Register</button>
    </form>
    <div class="helper">Already have an account? <a href="{{ route('customer.login') }}">Login</a></div>
  </div>
</body>
</html>
