<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Регистрация — TrackMoney</title>
    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/auth.css" />
</head>

<body>
    <header>
        <div class="container header-flex">
            <div class="logo">
                <img src="../images/smartphone-green.png" alt="TrackMoney logo" class="logo-img" />
                <span class="logo-text">TrackMoney</span>
            </div>
        </div>
    </header>
    <main>
        <section class="auth-section">
            <div class="container auth-container card">
                <h2 class="dashboard-title">Регистрация</h2>
                <?php
          session_start();
          if (isset($_SESSION['error'])) {
              echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
              unset($_SESSION['error']);
          }
          ?>
                <form id="registerForm" method="POST" action="../includes/auth.php">
                    <input type="hidden" name="action" value="register" />
                    <input type="text" name="username" placeholder="Логин" required />
                    <input type="password" name="password" placeholder="Пароль" required id="passwordInput" />
                    <input type="password" name="password_confirm" placeholder="Повторите пароль" required />
                    <div id="passwordError"
                        style="color:#f87171; display:none; font-size:1rem; text-align:center; margin-bottom:10px;">
                    </div>
                    <button type="submit" class="btn-main">Зарегистрироваться</button>
                </form>
                <script>
                document.getElementById('registerForm').addEventListener('submit', function(e) {
                    var username = document.querySelector('input[name="username"]').value;
                    var password = document.getElementById('passwordInput').value;
                    var errorDiv = document.getElementById('passwordError');
                    if (username.length < 3) {
                        e.preventDefault();
                        errorDiv.textContent = 'Логин должен быть не менее 3 символов';
                        errorDiv.style.display = 'block';
                    } else if (password.length < 8) {
                        e.preventDefault();
                        errorDiv.textContent = 'Пароль должен быть не менее 8 символов';
                        errorDiv.style.display = 'block';
                    } else {
                        errorDiv.style.display = 'none';
                    }
                });
                </script>
                <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
            </div>
        </section>
    </main>
    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 TrackMoney</p>
            <div class="social-links">
                <a href="https://t.me/verapetr16" target="_blank" class="social-button telegram">
                    <img src="../images/telegram.png" alt="Telegram" class="social-icon" />
                </a>
            </div>
        </div>
    </footer>
</body>

</html>