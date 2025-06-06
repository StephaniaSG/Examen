<?php
session_start();
require_once 'config.php';

// Если пользователь уже авторизован, перенаправляем на главную страницу
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Обработка формы регистрации
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $college = $_POST['college'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';
    
    // Проверка заполнения всех полей
    if (empty($name) || empty($email) || empty($college) || empty($password) || empty($password_confirmation)) {
        $error = 'Пожалуйста, заполните все поля';
    } 
    // Проверка совпадения паролей
    elseif ($password !== $password_confirmation) {
        $error = 'Пароли не совпадают';
    }
    // Проверка длины пароля
    elseif (strlen($password) < 8) {
        $error = 'Пароль должен содержать не менее 8 символов';
    }
    // Проверка существования email
    else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            $error = 'Пользователь с таким email уже существует';
        } else {
            // Регистрация нового пользователя
            $stmt = $pdo->prepare("INSERT INTO users (name, email, college_name, password, role, created_at) VALUES (?, ?, ?, ?, 'user', NOW())");
            if ($stmt->execute([$name, $email, $college, password_hash($password, PASSWORD_DEFAULT)])) {
                $success = 'Регистрация успешно завершена. Теперь вы можете войти в систему.';
                
                // Автоматический вход после регистрации
                $user_id = $pdo->lastInsertId();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = 'user';
                
                header('Location: index.php');
                exit();
            } else {
                $error = 'Ошибка при регистрации. Пожалуйста, попробуйте еще раз.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кафе Santo-Stefania</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center">
                <i class="fas fa-puzzle-piece text-green-300 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-800">Кафе Santo-Stefania</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="colleges.php" class="text-gray-700 hover:text-blue-600 transition">Колледжи</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="text-gray-700 hover:text-blue-600 transition">Мой профиль</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="text-gray-700 hover:text-blue-600 transition">Админ-панель</a>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="md:hidden text-gray-700 focus:outline-none" id="mobileMenuButton">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    Выйти
                </a>
                <?php else: ?>
                <a href="login.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Войти
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-2 flex flex-col space-y-2">
                <a href="colleges.php" class="py-2 text-gray-700 hover:text-blue-600 transition">Заказы</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="py-2 text-gray-700 hover:text-blue-600 transition">Мой профиль</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="py-2 text-gray-700 hover:text-blue-600 transition">Админ-панель</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="py-2 text-red-600 hover:text-red-700 transition">Выйти</a>
                <?php else: ?>
                <a href="login.php" class="py-2 text-blue-600 hover:text-blue-700 transition">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Регистрация</h1>
                
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($success); ?></span>
                </div>
                <?php endif; ?>
                
                <!-- Добавляем div для уведомлений -->
                <div id="notification" class="hidden bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline" id="notificationText"></span>
                </div>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                   
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Минимум 8 символов</p>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Подтверждение пароля</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        Зарегистрироваться
                    </button>
                </form>
                
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Уже есть аккаунт? <a href="login.php" class="text-blue-600 hover:text-blue-800">Войти</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
 <!-- Footer -->
 <footer class="bg-green-800 text-white py-8 mt-8">
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
            <p>&copy; 2025 |Кафе Santo-Stefania. Все права защищены.</p>
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
                notification.className += 'bg-red-100 border-red-400 text-red-700';
            } else {
                notification.className += 'bg-blue-100 border-blue-400 text-blue-700';
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