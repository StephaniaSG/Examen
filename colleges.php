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
    <title>SecTion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-pink-100 font-sans">
    <!-- Header -->
    <header class="bg-pink-700 shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center">
                    <i class="fas fa-puzzle-piece text-pink-300 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-pink-100">Кафе Santo-Stefania</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="clubs.php" class="text-pink-300 hover:text-pink-100 transition">Заказы</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="text-pink-300 hover:text-pink-100 transition">Мой профиль</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="text-pink-300 hover:text-pink-100 transition">Админ-панель</a>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="md:hidden text-pink-300 focus:outline-none" id="mobileMenuButton">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition">
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
        <div id="mobileMenu" class="hidden md:hidden bg-pink-700 border-t">
            <div class="container mx-auto px-4 py-2 flex flex-col space-y-2">
                <a href="clubs.php" class="py-2 text-pink-300 hover:text-pink-100 transition">Все заказы</a>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>" class="py-2 text-pink-300 hover:text-pink-100 transition">Мой профиль</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="py-2 text-pink-300 hover:text-pink-100 transition">Админ-панель</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="py-2 text-pink-300 hover:text-pink-100 transition">Выйти</a>
                <?php else: ?>
                <a href="login.php" class="py-2 text-pink-300 hover:text-pink-100 transition">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

  <!-- Main Content -->
<main class="container mx-auto px-4 py-8">
  <!-- Уведомления -->
  <div id="notification" class="hidden bg-pink-100 border border-pink-400 text-pink-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline" id="notificationText"></span>
  </div>

  <div class="max-w-2xl mx-auto">
    <!-- Заголовок и кнопки -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-pink-800">Управление системой</h1>
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin.php?action=add_employee" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition">
          <i class="fas fa-plus mr-2"></i>Добавить сотрудника
        </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Раздел: Сотрудники -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-pink-700 mb-4">Список сотрудников</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md border border-pink-300">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b border-pink-300">ФИО</th>
              <th class="py-2 px-4 border-b border-pink-300">Должность</th>
              <th class="py-2 px-4 border-b border-pink-300">Контакт</th>
              <th class="py-2 px-4 border-b border-pink-300">Действия</th>
            </tr>
          </thead>
          <tbody>
            <!-- Тут будут строки с данными сотрудников -->
            <!-- Пример строки -->
            <tr>
              <td class="py-2 px-4 border-b border-pink-300">Иван Иванов</td>
              <td class="py-2 px-4 border-b border-pink-300">Официант</td>
              <td class="py-2 px-4 border-b border-pink-300">+7 123 456 7890</td>
              <td class="py-2 px-4 border-b border-pink-300">
                <button class="bg-pink-500 text-white px-2 py-1 rounded hover:bg-pink-600">Редактировать</button>
                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 ml-2">Удалить</button>
              </td>
            </tr>
            <!-- Далее список -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- Раздел: Смены -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-pink-700 mb-4">Смены</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md border border-pink-300">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b border-pink-300">Дата</th>
              <th class="py-2 px-4 border-b border-pink-300">Время</th>
              <th class="py-2 px-4 border-b border-pink-300">Ответственный</th>
              <th class="py-2 px-4 border-b border-pink-300">Действия</th>
            </tr>
          </thead>
          <tbody>
            <!-- Строки смен -->
            <tr>
              <td class="py-2 px-4 border-b border-pink-300">2024-04-27</td>
              <td class="py-2 px-4 border-b border-pink-300">09:00 - 15:00</td>
              <td class="py-2 px-4 border-b border-pink-300">Иван Иванов</td>
              <td class="py-2 px-4 border-b border-pink-300">
                <button class="bg-pink-500 text-white px-2 py-1 rounded hover:bg-pink-600">Редактировать</button>
                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 ml-2">Удалить</button>
              </td>
            </tr>
            <!-- Другие смены -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- Раздел: Заказы (для официанта и повара) -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-pink-700 mb-4">Заказы</h2>
      <!-- Фильтр по сменам -->
      <div class="mb-4 flex items-center space-x-4">
        <label class="flex items-center space-x-2">
          <input type="radio" name="order_filter" value="all" checked class="form-radio text-pink-600" />
          <span>Все заказы</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="radio" name="order_filter" value="current" class="form-radio text-pink-600" />
          <span>Текущие смены</span>
        </label>
      </div>
      <!-- Таблица заказов -->
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md border border-pink-300">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b border-pink-300">Заказ №</th>
              <th class="py-2 px-4 border-b border-pink-300">Клиент</th>
              <th class="py-2 px-4 border-b border-pink-300">Статус</th>
              <th class="py-2 px-4 border-b border-pink-300">Действия</th>
            </tr>
          </thead>
          <tbody>
            <!-- Строки заказов -->
            <tr>
              <td class="py-2 px-4 border-b border-pink-300">101</td>
              <td class="py-2 px-4 border-b border-pink-300">Алексей</td>
              <td class="py-2 px-4 border-b border-pink-300">Принят</td>
              <td class="py-2 px-4 border-b border-pink-300">
                <button class="bg-pink-500 text-white px-2 py-1 rounded hover:bg-pink-600">Изменить статус</button>
                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 ml-2">Удалить</button>
              </td>
            </tr>
            <!-- Другие заказы -->
          </tbody>
        </table>
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
