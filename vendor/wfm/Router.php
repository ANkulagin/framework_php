<?php


namespace wfm;


class Router
{

    protected static array $routes = [];
    protected static array $route = [];

    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRoute(): array
    {
        return self::$route;
    }
    protected static function removeQueryString($url)
    {
        if ($url){
            $params=explode('&',$url,2);
            if (false === str_contains($params[0], '=')){
                return rtrim($params[0],'/');
            }
        }
        return '';
    }

    /**
     * @throws \Exception
     */
    public static function dispatch($url): void
    {
        $url=self::removeQueryString($url);
        if (self::matchRoute($url)) {
            $controller= 'app\controllers\\' . self::$route['admin_prefix'] . self::$route['controller'] . 'Controller';
            if (class_exists($controller)){
                $controllerObject=new $controller(self::$route);
                $action=self::lowerCamelCase(self::$route['action'] . 'Action');
                if (method_exists($controllerObject, $action)){
                    $controllerObject->$action();
                }else{
                    throw new \Exception("Контроллер {$controller} не найден",404);
                }
            }
            else{
                throw new \Exception("Контроллер {$controller} не найден",404);
            }
        } else {
            throw new \Exception("Страница не найдена",404);
        }
    }

    public static function matchRoute($url): bool
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#{$pattern}#", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }
                if (!isset($route['admin_prefix'])) {
                    $route['admin_prefix'] = '';
                } else {
                    $route['admin_prefix'] .= '\\';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route=$route;
                return true;
            }
        }
        return false;
    }

    // CamelCase
    protected static function upperCamelCase($name): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    // camelCase
    protected static function lowerCamelCase($name): string
    {
        return lcfirst(self::upperCamelCase($name));
    }

}






/*
 * Данный код представляет класс "Router", который является частью механизма маршрутизации в PHP приложении. Маршрутизация позволяет обрабатывать запросы пользователя и определить, какой контроллер и метод должны быть вызваны для обработки этого запроса.

Теперь разберем каждую строчку кода и объясним ее:

php
Copy code
namespace wfm;
Здесь объявляется пространство имен "wfm" для класса "Router". Это позволяет изолировать данный класс от других классов с таким же именем, что обеспечивает более чистую структуру кода.

php
Copy code
class Router
{
Объявление класса "Router".

php
Copy code
protected static array $routes = [];
protected static array $route = [];
Декларация статических свойств "$routes" и "$route" класса "Router", которые будут хранить маршруты и текущий маршрут соответственно.

php
Copy code
public static function add($regexp, $route = [])
{
    self::$routes[$regexp] = $route;
}
Метод "add" предназначен для добавления маршрута в массив маршрутов "$routes". Он принимает два параметра: "$regexp" - регулярное выражение для сопоставления маршрута, и "$route" - ассоциативный массив с параметрами маршрута. Метод сохраняет переданный маршрут в массиве маршрутов по ключу "$regexp".

php
Copy code
public static function getRoutes(): array
{
    return self::$routes;
}
Метод "getRoutes" возвращает массив всех маршрутов "$routes".

php
Copy code
public static function getRoute(): array
{
    return self::$route;
}
Метод "getRoute" возвращает массив текущего маршрута "$route".

php
Copy code
public static function dispatch($url)
{
    if (self::matchRoute($url)) {
        echo 'OK';
    } else {
        echo 'NO';
    }
}
Метод "dispatch" выполняет обработку запроса, который представлен в параметре "$url". Он вызывает метод "matchRoute", чтобы проверить, соответствует ли данный URL какому-либо маршруту. Если соответствие найдено, выводится сообщение "OK", иначе - "NO".

php
Copy code
public static function matchRoute($url): bool
{
    foreach (self::$routes as $pattern => $route) {
        if (preg_match("#{$pattern}#", $url, $matches)) {
            // Обработка параметров маршрута и преобразование названия контроллера
            // ...
            return true;
        }
    }
    return false;
}
Давайте разберем каждую строку метода matchRoute в классе Router:

php
Copy code
public static function matchRoute($url): bool
{
    foreach (self::$routes as $pattern => $route) {
        if (preg_match("#{$pattern}#", $url, $matches)) {
public static function matchRoute($url): bool: Это определение метода matchRoute, который принимает $url (запрошенный URL) в качестве параметра и возвращает булево значение (true или false).

foreach (self::$routes as $pattern => $route): Это цикл foreach, который проходит по массиву $routes, где $pattern - ключ (регулярное выражение маршрута), а $route - значение (ассоциативный массив с параметрами маршрута).

if (preg_match("#{$pattern}#", $url, $matches)): Это проверка, соответствует ли $url регулярному выражению $pattern. Функция preg_match используется для сравнения $url с регулярным выражением $pattern. Если совпадение найдено, то результат сохраняется в массив $matches.

php
Copy code
foreach ($matches as $k => $v) {
    if (is_string($k)) {
        $route[$k] = $v;
    }
}
В этом блоке кода происходит обработка параметров маршрута, которые были найдены с помощью регулярного выражения. Цикл foreach перебирает массив $matches, где $k - это ключ параметра, а $v - его значение. Если ключ $k является строкой (не числом), это означает, что параметр был именованным (с использованием именованных подмасок в регулярном выражении), и тогда его значение сохраняется в массиве $route по ключу $k.
php
Copy code
if (empty($route['action'])) {
    $route['action'] = 'index';
}
Если параметр 'action' отсутствует или его значение пусто, то присваивается значение 'index'. Это обеспечивает дефолтное значение для действия (метода) контроллера, если оно не указано явно в URL.
php
Copy code
if (!isset($route['admin_prefix'])) {
    $route['admin_prefix'] = '';
} else {
    $route['admin_prefix'] = '\\';
}
Здесь проверяется наличие параметра 'admin_prefix' в массиве $route. Если параметр отсутствует, то ему присваивается пустая строка, иначе ему присваивается символ обратного слеша '\\'.
php
Copy code
debug($route);
$route['controller'] = self::upperCamelCase($route['controller']);
debug($route);
return true;
Данный код использует функцию debug для вывода содержимого массива $route на экран (предполагается, что эта функция предназначена для отладки). Затем, для параметра 'controller' в массиве $route применяется метод upperCamelCase, чтобы преобразовать его в верхний CamelCase формат (например, "some-controller" преобразуется в "SomeController").

В конце метода, когда маршрут успешно найден, возвращается значение true, чтобы указать, что соответствующий маршрут был найден. Если ни один из маршрутов не соответствует $url, то метод вернет false.

php
Copy code
protected static function upperCamelCase($name): string
{
    return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
}
Метод "upperCamelCase" преобразует переданную строку в верхний CamelCase формат, то есть каждое слово в строке начинается с заглавной буквы без пробелов или дефисов.

php
Copy code
protected static function lowerCamelCase($name): string
{
    return lcfirst(self::upperCamelCase($name));
}
Метод "lowerCamelCase" преобразует переданную строку в нижний camelCase формат, то есть первое слово в строке начинается с маленькой буквы, а остальные слова начинаются с заглавной буквы без пробелов или дефисов.

Основное назначение класса "Router" - обеспечить маршрутизацию запросов в приложении и определить, какой контроллер и метод должны быть вызваны для обработки каждого запроса. Он использует регулярные выражения для сопоставления URL с заданными маршрутами и определяет параметры запроса, такие как контроллер и действие (метод), которые должны быть вызваны.
 */






/*
 * Данный код представляет класс "Router", который является частью механизма маршрутизации в PHP приложении. Маршрутизация позволяет обрабатывать запросы пользователя и определить, какой контроллер и метод должны быть вызваны для обработки этого запроса.

Теперь разберем каждую строчку кода и объясним ее:

php
Copy code
namespace wfm;
Здесь объявляется пространство имен "wfm" для класса "Router". Это позволяет изолировать данный класс от других классов с таким же именем, что обеспечивает более чистую структуру кода.

php
Copy code
class Router
{
Объявление класса "Router".

php
Copy code
protected static array $routes = [];
protected static array $route = [];
Декларация статических свойств "$routes" и "$route" класса "Router", которые будут хранить маршруты и текущий маршрут соответственно.

php
Copy code
public static function add($regexp, $route = [])
{
    self::$routes[$regexp] = $route;
}
Метод "add" предназначен для добавления маршрута в массив маршрутов "$routes". Он принимает два параметра: "$regexp" - регулярное выражение для сопоставления маршрута, и "$route" - ассоциативный массив с параметрами маршрута. Метод сохраняет переданный маршрут в массиве маршрутов по ключу "$regexp".

php
Copy code
public static function getRoutes(): array
{
    return self::$routes;
}
Метод "getRoutes" возвращает массив всех маршрутов "$routes".

php
Copy code
public static function getRoute(): array
{
    return self::$route;
}
Метод "getRoute" возвращает массив текущего маршрута "$route".

php
Copy code
public static function dispatch($url)
{
    if (self::matchRoute($url)) {
        echo 'OK';
    } else {
        echo 'NO';
    }
}
Метод "dispatch" выполняет обработку запроса, который представлен в параметре "$url". Он вызывает метод "matchRoute", чтобы проверить, соответствует ли данный URL какому-либо маршруту. Если соответствие найдено, выводится сообщение "OK", иначе - "NO".

php
Copy code
public static function matchRoute($url): bool
{
    foreach (self::$routes as $pattern => $route) {
        if (preg_match("#{$pattern}#", $url, $matches)) {
            // Обработка параметров маршрута и преобразование названия контроллера
            // ...
            return true;
        }
    }
    return false;
}
Метод "matchRoute" осуществляет проверку, соответствует ли переданный URL какому-либо из маршрутов, определенных в массиве "$routes". Для каждого маршрута выполняется проверка с помощью регулярного выражения. Если соответствие найдено, то метод возвращает "true", иначе - "false".

php
Copy code
protected static function upperCamelCase($name): string
{
    return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
}
Метод "upperCamelCase" преобразует переданную строку в верхний CamelCase формат, то есть каждое слово в строке начинается с заглавной буквы без пробелов или дефисов.

php
Copy code
protected static function lowerCamelCase($name): string
{
    return lcfirst(self::upperCamelCase($name));
}
Метод "lowerCamelCase" преобразует переданную строку в нижний camelCase формат, то есть первое слово в строке начинается с маленькой буквы, а остальные слова начинаются с заглавной буквы без пробелов или дефисов.

Основное назначение класса "Router" - обеспечить маршрутизацию запросов в приложении и определить, какой контроллер и метод должны быть вызваны для обработки каждого запроса. Он использует регулярные выражения для сопоставления URL с заданными маршрутами и определяет параметры запроса, такие как контроллер и действие (метод), которые должны быть вызваны.
 */