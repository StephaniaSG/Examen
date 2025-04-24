<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'login':
                $email = $_POST['email'];
                $password = $_POST['password'];
                
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && $password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['college_name'] = $user['college_name'];
                    
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION['error'] = 'Неверный email или пароль';
                    header('Location: login.php');
                    exit();
                }
                break;
                
            case 'register':
                $email = $_POST['email'];
                $password = $_POST['password'];
                $college_name = $_POST['college_name'];
                
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (email, password, college_name) VALUES (?, ?, ?)");
                    $stmt->execute([$email, $password, $college_name]);
                    
                    $_SESSION['success'] = 'Регистрация успешна! Теперь вы можете войти.';
                    header('Location: login.php');
                    exit();
                } catch (PDOException $e) {
                    $_SESSION['error'] = 'Ошибка регистрации: ' . $e->getMessage();
                    header('Location: register.php');
                    exit();
                }
                break;
                
            case 'logout':
                session_destroy();
                header('Location: index.php');
                exit();
                break;
        }
    }
} 