<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Вход — TrackMoney</title>
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
                <h2 class="dashboard-title">Вход</h2>
                <?php
          session_start();
          if (isset($_SESSION['error'])) {
              echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
              unset($_SESSION['error']);
          }
          ?>
                <form id="loginForm" method="POST" action="../includes/auth.php">
                    <input type="hidden" name="action" value="login" />
                    <input type="text" name="username" placeholder="Логин" required />
                    <input type="password" name="password" placeholder="Пароль" required />
                    <button type="submit" class="btn-main">Войти</button>
                </form>
                <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
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