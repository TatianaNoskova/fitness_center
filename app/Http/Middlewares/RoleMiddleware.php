<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Проверяем, авторизован ли пользователь
        if (!auth()->check()) {
            return redirect('/login'); // Вместо 401 ошибки гостей лучше мягко отправлять на логин
        }

        $user = auth()->user();


        // 2. Приводим роль пользователя к нижнему регистру на всякий случай
        $userRole = strtolower($user->rol);

        // 3. Проверяем, есть ли роль пользователя в списке разрешенных для этого роута
        // Массив $roles содержит то, что мы передадим в файле роутов
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Если роль не подошла — доступ закрыт
        abort(403, 'Acceso denegado. No tienes los permisos necesarios.');
    }
}