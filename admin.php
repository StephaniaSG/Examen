<?php
session_start();
require_once 'config.php';

// Проверяем, авторизован ли пользователь и имеет ли он роль администратора
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
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
    <title>Админ-панель - Video</title>
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
                    <span class="text-xl font-bold text-gray-800">Video</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="colleges.php" class="text-gray-700 hover:text-blue-600 transition">Колледжи</a>
                <a href="profile.php" class="text-gray-700 hover:text-blue-600 transition">Мой профиль</a>
                <a href="admin.php" class="text-gray-700 hover:text-blue-600 transition">Админ-панель</a>
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
                <a href="admin.php" class="py-2 text-gray-700 hover:text-blue-600 transition">Админ-панель</a>
                <a href="logout.php" class="py-2 text-red-600 hover:text-red-700 transition">Выйти</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Админ-панель</h1>
                <div class="flex space-x-2">
                    <a href="admin.php?action=add_college" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Добавить колледж
                    </a>
                    <a href="admin.php?action=add_video" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-video mr-2"></i>Добавить видео
                    </a>
                </div>
            </div>
            
            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-university text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Колледжи</p>
                            <p class="text-2xl font-bold text-gray-800">6</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-video text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Видео</p>
                            <p class="text-2xl font-bold text-gray-800">69</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Пользователи</p>
                            <p class="text-2xl font-bold text-gray-800">124</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-eye text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Просмотры</p>
                            <p class="text-2xl font-bold text-gray-800">1,234</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Последние видео -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Последние видео</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Колледж</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Просмотры</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Введение в программирование</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Технический колледж №1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15.03.2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">245</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="admin.php?action=edit_video&id=1" class="text-blue-600 hover:text-blue-900 mr-3">Редактировать</a>
                                    <a href="admin.php?action=delete_video&id=1" class="text-red-600 hover:text-red-900">Удалить</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Основы Python для начинающих</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Технический колледж №1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10.03.2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">189</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="admin.php?action=edit_video&id=2" class="text-blue-600 hover:text-blue-900 mr-3">Редактировать</a>
                                    <a href="admin.php?action=delete_video&id=2" class="text-red-600 hover:text-red-900">Удалить</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Анатомия человека</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Медицинский колледж</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">05.03.2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">312</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="admin.php?action=edit_video&id=3" class="text-blue-600 hover:text-blue-900 mr-3">Редактировать</a>
                                    <a href="admin.php?action=delete_video&id=3" class="text-red-600 hover:text-red-900">Удалить</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Быстрые действия -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Быстрые действия</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="admin.php?action=add_college" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-plus text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Добавить колледж</p>
                            <p class="text-sm text-gray-500">Создать новое учебное заведение</p>
                        </div>
                    </a>
                    
                    <a href="admin.php?action=add_video" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-video text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Загрузить видео</p>
                            <p class="text-sm text-gray-500">Добавить новое учебное видео</p>
                        </div>
                    </a>
                    
                    <a href="admin.php?action=manage_users" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Управление пользователями</p>
                            <p class="text-sm text-gray-500">Просмотр и редактирование пользователей</p>
                        </div>
                    </a>
                    
                    <a href="admin.php?action=settings" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-cog text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Настройки системы</p>
                            <p class="text-sm text-gray-500">Конфигурация и параметры сайта</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-8">
        <div class="container mx-auto px-4">
            <div class="text-center text-gray-400">
                <p>&copy; 2023 EduVideo. Все права защищены.</p>
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