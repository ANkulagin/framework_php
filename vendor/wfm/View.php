<?php

namespace wfm;

class View
{
    public string $content = '';
    public function __construct(
        public $route,
        public $layout='',
        public $view='',
        public $meta=[],
    )
    {
        if (false !== $this->layout){
            $this->layout = $this->layout ?:LAYOUT;
        }
    }

    public function render($data)
    {
        if (is_array($data)){
            extract($data);
        }
        $prefix =str_replace('\\','/',$this->route['admin_prefix']);
        $view_file=APP . "/views/{$prefix}{$this->route['controller']}/{$this->view}.php";
        if (is_file($view_file)){
            ob_start();
            require_once $view_file;
            $this->content=ob_get_clean();
        }else{
            throw new \Exception("не найден вид{$view_file}", 500);
        }
        if (false !== $this->layout){
            $layout_file = APP . "/views/layouts/{$this->layout}.php";
            if (is_file($layout_file)){
                require_once $layout_file;
            }else{
                throw new \Exception("не найден шаблон {$layout_file}",500);
            }
        }
    }
    public function getMeta()
    {
        $out='<title>' . htmlspecialchars($this->meta['title'] ). '</title>' .PHP_EOL;
        $out .='<meta name="description" content="'.htmlspecialchars( $this->meta['description']) .'">' .PHP_EOL;
        $out .='<meta name="keywords" content="'. htmlspecialchars($this->meta['keywords']) .'">' .PHP_EOL;
        return $out;
    }
}





/*
 * Данный код представляет класс View, который отвечает за рендеринг (отображение) представления (шаблона) и передачу данных в представление. Рассмотрим каждую строку кода подробнее:

php
Copy code
namespace wfm;
Эта строка определяет пространство имен, в котором находится класс View. Пространство имен предназначено для организации и структурирования классов и позволяет избежать конфликтов имен классов.

php
Copy code
class View
{
    public string $content = '';
Класс View содержит одно открытое свойство $content, которое будет хранить контент представления.

php
Copy code
public function __construct(
    public $route,
    public $layout='',
    public $view='',
    public $meta=[],
)
Это конструктор класса View, который принимает четыре аргумента:

$route: массив с информацией о текущем маршруте (контроллер, действие и т.д.).
$layout: имя файла шаблона (если он задан) для обертки контента представления.
$view: имя файла представления (шаблона), который будет отображаться.
$meta: массив с метаданными, такими как заголовок страницы, описание, ключевые слова и т.д.
php
Copy code
if (false !== $this->layout){
    $this->layout = $this->layout ?:LAYOUT;
}
Здесь проверяется, задан ли шаблон для обертки контента. Если $this->layout не равен false, то используется заданный шаблон или, если он не задан (пустая строка), используется значение константы LAYOUT.

php
Copy code
public function render($data)
{
    if (is_array($data)){
        extract($data);
    }
Метод render() отвечает за рендеринг представления с учетом данных, переданных в параметре $data. Если переданные данные являются массивом, функция extract() используется для извлечения элементов массива в виде переменных, чтобы их можно было использовать в представлении без необходимости обращения к ним через индексы.

php
Copy code
$prefix = str_replace('\\', '/', $this->route['admin_prefix']);
$view_file = APP . "/views/{$prefix}{$this->route['controller']}/{$this->view}.php";
Здесь формируется путь к файлу представления на основе имени контроллера и представления, а также префикса, который может использоваться для разделения представлений в разных подпапках в зависимости от контекста (например, для административной части сайта).

php
Copy code
if (is_file($view_file)){
    ob_start();
    require_once $view_file;
    $this->content = ob_get_clean();
} else {
    throw new \Exception("Не найден вид {$view_file}", 500);
}
Здесь происходит проверка наличия файла представления. Если файл существует, используется буферизация вывода (ob_start()) для сохранения вывода в переменную $this->content. Затем файл представления подключается с помощью require_once и сохраненный контент помещается в свойство $this->content. Если файл представления не найден, выбрасывается исключение с сообщением об ошибке.

php
Copy code
if (false !== $this->layout){
    $layout_file = APP . "/views/layouts/{$this->layout}.php";
    if (is_file($layout_file)){
        require_once $layout_file;
    } else {
        throw new \Exception("Не найден шаблон {$layout_file}", 500);
    }
}
Если установлен шаблон для обертки контента ($this->layout не равен false), то формируется путь к файлу шаблона обертки. Если файл существует, он подключается с помощью require_once, и контент представления $this->content вставляется в место, где находится метка контента в шаблоне. Если файл шаблона не найден, выбрасывается исключение с сообщением об ошибке.
 */