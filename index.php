<?php
session_start();
require_once 'config.php';

// Получаем данные пользователя, если он авторизован
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ИС управления заведениями общественного питания типа – кафе </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-50 font-sans">
    <!-- Auth Modals -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md modal-animation">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Вход в систему</h2>
                <button onclick="closeModal('loginModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="loginForm">
                <div class="mb-4">
                    <label for="loginEmail" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" id="loginEmail" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="loginPassword" class="block text-gray-700 mb-2">Пароль</label>
                    <input type="password" id="loginPassword" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="rememberMe" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="rememberMe" class="ml-2 block text-sm text-gray-700">Запомнить меня</label>
                    </div>
                    <a href="#" onclick="showModal('forgotModal')" class="text-sm text-blue-600 hover:underline">Забыли пароль?</a>
                </div>
                <button type="submit" class="w-full bg-black-600 text-white py-2 px-4 rounded-lg hover:bg-black-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Войти
                </button>
            </form>
            <div class="mt-4 text-center">
                <p class="text-gray-600">Нет аккаунта? <a href="#" onclick="showModal('registerModal')" class="text-blue-600 hover:underline">Зарегистрироваться</a></p>
            </div>
        </div>
    </div>

    <div id="registerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md modal-animation">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Регистрация</h2>
                <button onclick="closeModal('registerModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="registerForm">
                <div class="mb-4">
                    <label for="registerCollegeName" class="block text-gray-700 mb-2">Название учебного заведения</label>
                    <input type="text" id="registerCollegeName" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="registerEmail" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" id="registerEmail" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="registerPassword" class="block text-gray-700 mb-2">Пароль</label>
                    <input type="password" id="registerPassword" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="registerConfirmPassword" class="block text-gray-700 mb-2">Подтвердите пароль</label>
                    <input type="password" id="registerConfirmPassword" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Зарегистрироваться
                </button>
            </form>
            <div class="mt-4 text-center">
                <p class="text-gray-600">Уже есть аккаунт? <a href="#" onclick="showModal('loginModal')" class="text-pink-600 hover:underline">Войти</a></p>
            </div>
        </div>
    </div>

    <div id="forgotModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md modal-animation">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Восстановление пароля</h2>
                <button onclick="closeModal('forgotModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="forgotForm">
                <div class="mb-6">
                    <label for="forgotEmail" class="block text-gray-700 mb-2">Введите ваш email</label>
                    <input type="email" id="forgotEmail" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="mt-2 text-sm text-gray-500">Мы отправим ссылку для восстановления пароля на этот адрес.</p>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Отправить
                </button>
            </form>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-pink shadow-sm"><main class="container mx-auto px-4 py-8 max-w-5xl">

        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center">
                <i class="fas fa-puzzle-piece text-pink-300 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-pink-800">Kafe</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="colleges.php" class="text-black-700 hover:text-pink-600 transition">Администраторы <i class="fa fa-coffee" aria-hidden="true"></i></a>
                <a href="menyou.php" class="text-black-700 hover:text-pink-600 transition">Меню <i class="fa fa-coffee" aria-hidden="true"></i></a>
                <a href="oficiant.php" class="text-black-700 hover:text-pink-600 transition">Официанты</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="text-black-700 hover:text-blue-600 transition">Авторизация</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="text-gray-700 hover:text-blue-600 transition">Админ-панель</a>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="md:hidden text-purple-700 focus:outline-none" id="mobileMenuButton">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
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
                <a href="colleges.php" class="py-2 text-gray-700 hover:text-blue-600 transition">Заказики</a>
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

    <!-- Hero Section -->
    <section class="bg-pink-600 text-black py-14">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">


                <h1 class="text-4xl md:text-5xl font-bold mb-6">|Кафе Santo-Stefania </h1>
                <p class="text-xl text-white mb-8">
                Заказ лучшего кофе только у нас!
</p>

                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="menyou.php" style="background-color: pink; color: black; padding: 12px 24px; border-radius: 10px; font-size: 16px; transition: background-color 0.3s ease;">
  Заказать кофе <i class="fa fa-coffee" aria-hidden="true"></i>
</a>

                    <?php if (!isset($_SESSION['user_id'])): ?>
    <a href="login.php" class="bg-black text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-900 transition">
        Войти в аккаунт
    </a>
<?php endif; ?>

                </div>
            </div>
        </div>
    </section>






<!-- Latest Videos Section (only for logged in users) -->
<?php if (isset($_SESSION['user_id'])): ?>
<section class="py-16 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-pink-800 mb-8">Последние курсы</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Course 1 -->
            <div class="bg-purple-200 rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-purple-400 flex items-center justify-center">
                    <i class="fas fa-play-circle text-purple-800 text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-purple-800 mb-2">Основы программирования</h3>
                    <p class="text-purple-600 mb-4">Технический колледж</p>
                    <div class="flex justify-between items-center text-sm text-purple-500">
                        <span><i class="fas fa-calendar-alt mr-1"></i> 20.03.2023</span>
                        <span><i class="fas fa-eye mr-1"></i> 300 просмотров</span>
                    </div>
                </div>
            </div>
            
            <!-- Course 2 -->
            <div class="bg-purple-200 rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-purple-400 flex items-center justify-center">
                    <i class="fas fa-play-circle text-purple-800 text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-purple-800 mb-2">Математика для начинающих</h3>
                    <p class="text-purple-600 mb-4">Математический колледж</p>
                    <div class="flex justify-between items-center text-sm text-purple-500">
                        <span><i class="fas fa-calendar-alt mr-1"></i> 15.03.2023</span>
                        <span><i class="fas fa-eye mr-1"></i> 200 просмотров</span>
                    </div>
                </div>
            </div>
            
            <!-- Course 3 -->
            <div class="bg-purple-200 rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-purple-400 flex items-center justify-center">
                    <i class="fas fa-play-circle text-purple-800 text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-purple-800 mb-2">Биология человека</h3>
                    <p class="text-purple-600 mb-4">Биологический колледж</p>
                    <div class="flex justify-between items-center text-sm text-purple-500">
                        <span><i class="fas fa-calendar-alt mr-1"></i> 10.03.2023</span>
                        <span><i class="fas fa-eye mr-1"></i> 400 просмотров</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-8">
            <a href="colleges.php" class="inline-block bg-purple-800 text-white px-6 py-3 rounded-lg font-medium hover:bg-purple-900 transition">
                Смотреть все курсы
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

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
            <p>&copy; 2025 |Kafe. Все права защищены.</p>
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