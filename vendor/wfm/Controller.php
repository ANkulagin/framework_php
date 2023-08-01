<?php

namespace wfm;

abstract class Controller
{
    public array $data =[];
    public array $meta =[];
    public false|string $layout='';
    public object $model;
    public string $view='';

    public function __construct(public $route=[])
    {

    }
    public function getModel()
    {
        $model ='app\models\\' . $this->route['admin_prefix'] . $this->route['controller'];
        if (class_exists($model)){
            $this->model = new $model();
        }
     }
    public function getView()
    {
        $this->view=$this->view ?: $this->route['action'];
        (new View($this->route, $this->layout, $this->view, $this->meta))->render($this->data);
    }
    public function set($data)
    {
        $this->data =$data;
    }
    public function setMeta($title='',$description='',$keywords='')
    {
        $this->meta=[
            'title'=>$title, 'description'=>$description,'keywords'=>$keywords
        ];
    }
}


/*
 * Данный код представляет абстрактный класс Controller, который служит в качестве базового класса для контроллеров в приложении. Контроллеры используются в архитектуре MVC (Model-View-Controller) для обработки пользовательских запросов, взаимодействия с моделью (данными) и передачи данных в представление (шаблон).

Объяснение каждой строки кода:

namespace wfm;: Указывает на то, что класс Controller находится в пространстве имен wfm.

abstract class Controller: Определяет абстрактный класс Controller.

public array $data =[];: Объявляет свойство $data, которое будет содержать данные, передаваемые в представление.

public array $meta =[];: Объявляет свойство $meta, которое будет содержать метаданные (например, заголовок страницы, описание, ключевые слова).

public false|string $layout='';: Объявляет свойство $layout, которое будет содержать имя файла шаблона представления (layout) или быть пустой строкой, если шаблон не используется.

public object $model;: Объявляет свойство $model, которое будет содержать объект модели, отвечающей за взаимодействие с данными.

public string $view='';: Объявляет свойство $view, которое будет содержать имя файла представления.

public function __construct(public $route=[]): Конструктор класса. Принимает массив $route в качестве аргумента, который содержит информацию о текущем маршруте, переданном контроллеру.

Данный код создает объект модели ($this->model) на основе имени класса, которое формируется из информации о маршруте, хранящейся в свойстве $route.

Предположим, у нас есть URL-адрес /admin/product/create, и приложение обрабатывает данный URL с помощью маршрута 'admin/product/create', который содержится в свойстве $route. В данном случае, $this->route['admin_prefix'] будет равно 'admin', а $this->route['controller'] будет равно 'product'.

Строка $model = 'app\models\\' . $this->route['admin_prefix'] . $this->route['controller']; формирует строку, которая представляет имя класса модели. Например, если $this->route['admin_prefix'] равно 'admin', а $this->route['controller'] равно 'product', то $model будет равно 'app\models\admin\product'.

Далее, код проверяет, существует ли класс с таким именем, используя функцию class_exists($model). Если класс существует, то создается объект модели $this->model, используя конструктор этого класса. В результате, объект модели будет использоваться для взаимодействия с данными и выполнения необходимых действий при обработке запросов пользователя.

public function getView(): Метод для определения имени файла представления на основе информации из свойства $route.

public function set($data): Метод для установки данных в свойство $data.

public function setMeta($title='', $description='', $keywords=''): Метод для установки метаданных в свойство $meta.

Класс Controller является абстрактным, что означает, что он не может быть создан непосредственно (нельзя создать объект этого класса). Вместо этого, он используется в качестве базового класса для создания конкретных контроллеров, которые будут обрабатывать конкретные запросы пользователей в приложении. Классы-наследники Controller будут определять специфическую логику обработки запросов, а также определять, какие данные и метаданные передавать в представление.
 */