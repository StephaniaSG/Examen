<?php
session_start();
require_once 'config.php';

// Если пользователь уже авторизован, перенаправляем на главную страницу
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Обработка формы входа
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Пожалуйста, заполните все поля';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Успешная авторизация
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: index.php');
            exit();
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Video</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-indigo-50 font-sans">
   <!-- Header -->
   <header class="bg-pink-800 shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center">
                <i class="fas fa-puzzle-piece text-pink-300 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-purple-100">Кафе Santo-Stefania</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="colleges.php" class="text-purple-300 hover:text-purple-100 transition">Заказы</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="text-purple-300 hover:text-purple-100 transition">Мой профиль</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="text-purple-300 hover:text-purple-100 transition">Админ-панель</a>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="md:hidden text-purple-300 focus:outline-none" id="mobileMenuButton">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    Выйти
                </a>
                <?php else: ?>
                <a href="login.php" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition">
                    Войти
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-2 flex flex-col space-y-2">
                <a href="colleges.php" class="py-2 text-gray-700 hover:text-purple-600 transition">Колледжи</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="py-2 text-gray-700 hover:text-purple-600 transition">Мой профиль</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="py-2 text-gray-700 hover:text-purple-600 transition">Админ-панель</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="py-2 text-red-600 hover:text-red-700 transition">Выйти</a>
                <?php else: ?>
                <a href="login.php" class="py-2 text-purple-600 hover:text-purple-700 transition">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Вход в аккаунт <i class="fa fa-coffee" aria-hidden="true"></i></h1>
                
                <!-- Добавляем div для уведомлений -->
                <div id="notification" class="hidden bg-purple-100 border border-purple-400 text-purple-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline" id="notificationText"></span>
                </div>

                <form method="POST" class="space-y-4" id="loginForm">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">Запомнить меня</label>
                        </div>
                        <a href="forgot-password.php" class="text-sm text-purple-600 hover:text-purple-800">Забыли пароль?</a>
                    </div>
                    
                    <button type="submit" class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                        Войти
                    </button>
                </form>
                
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Нет аккаунта? <a href="register.php" class="text-purple-600 hover:text-purple-800">Зарегистрироваться</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-pink-800 text-white py-8 mt-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-300">Наша Миссия</h3>
                <p class="text-gray-400">лучший кофе на районе</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-300">Навигация</h3>
                <ul class="space-y-2">
                    <li><a href="index.php" class="text-gray-400 hover:text-gray-600 transition">Главная страница</a></li>
                    <li><a href="colleges.php" class="text-gray-400 hover:text-gray-600 transition">Каталог заказов</a></li>
                    <li><a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="text-gray-400 hover:text-gray-600 transition">Личный кабинет</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php" class="text-gray-400 hover:text-gray-600 transition">Панель администратора</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-300">Связаться с нами</h3>
                <ul class="space-y-2">
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-envelope mr-2"></i> info@section.com
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-phone mr-2"></i> +7 (999) 123-45-67
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025 |SecTion. Все права защищены.</p>
        </div>
    </div>
</footer>


<script>
    // Mobile menu toggle
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
    
    // Функция показа уведомления
    function showNotification(message, type = 'info') {
        const notification = document.getElementById('notification');
        const notificationText = document.getElementById('notificationText');
        
        // Устанавливаем текст уведомления
        notificationText.textContent = message;
        
        // Устанавливаем цвет в зависимости от типа
        notification.className = 'border px-4 py-3 rounded relative mb-4 ';
        if (type === 'error') {
            notification.className += 'bg-purple-100 border-purple-400 text-purple-700';
        } else {
            notification.className += 'bg-purple-100 border-purple-400 text-purple-700';
        }
        
        // Показываем уведомление
        notification.classList.remove('hidden');
        
        // Автоматически скрываем через 3 секунды
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }
</script>

</body>
</html>
