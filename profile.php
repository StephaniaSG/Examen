<?php
session_start();
require_once 'config.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Получаем данные пользователя из базы данных
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Если пользователь не найден, перенаправляем на страницу входа
if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой профиль - Video</title>
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
                    <i class="fas fa-graduation-cap text-blue-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-800">EduVideo</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="colleges.php" class="text-gray-700 hover:text-blue-600 transition">Колледжи</a>
                <a href="profile.php" class="text-gray-700 hover:text-blue-600 transition">Мой профиль</a>
                <?php if ($user['role'] === 'admin'): ?>
                <a href="admin.php" class="text-gray-700 hover:text-blue-600 transition">Админ-панель</a>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="md:hidden text-gray-700 focus:outline-none" id="mobileMenuButton">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    Выйти
                </a>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-2 flex flex-col space-y-2">
                <a href="colleges.php" class="py-2 text-gray-700 hover:text-blue-600 transition">Колледжи</a>
                <a href="profile.php" class="py-2 text-gray-700 hover:text-blue-600 transition">Мой профиль</a>
                <?php if ($user['role'] === 'admin'): ?>
                <a href="admin.php" class="py-2 text-gray-700 hover:text-blue-600 transition">Админ-панель</a>
                <?php endif; ?>
                <a href="logout.php" class="py-2 text-red-600 hover:text-red-700 transition">Выйти</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Мой профиль</h1>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-gray-400 text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($user['email']); ?></h2>
                        <p class="text-gray-600"><?php echo htmlspecialchars($user['college_name']); ?></p>
                        <p class="text-gray-500 text-sm">Роль: <?php echo $user['role'] === 'admin' ? 'Администратор' : 'Пользователь'; ?></p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Личная информация</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Колледж</label>
                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['college_name']); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Дата регистрации</label>
                            <p class="mt-1 text-gray-900"><?php echo date('d.m.Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">История просмотров</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название видео</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Колледж</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата просмотра</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Здесь будет история просмотров -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="3">История просмотров пока пуста</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-8">
        <div class="container mx-auto px-4">
            <div class="text-center text-gray-400">
                <p>&copy; 2023 Кафе Santo-Stefania. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>
</body>
</html> 