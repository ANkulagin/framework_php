<?php


namespace wfm;


trait TSingleton
{

    private static ?self $instance = null;

    private function __construct(){}

    public static function getInstance(): static
    {
        return static::$instance ?? static::$instance = new static();
    }

}




/*
 *
Данный код определяет трейт TSingleton, который реализует шаблон проектирования "Одиночка" (Singleton) для классов в пространстве имен wfm.

Шаблон "Одиночка" гарантирует, что класс имеет только один экземпляр, и предоставляет глобальную точку доступа к этому экземпляру.

Разберем код подробнее:

trait TSingleton: Это объявление трейта с именем TSingleton.

private static self|null $instance = null;: Это статическое свойство $instance, которое хранит единственный экземпляр класса. Переменная имеет тип self|null, что означает, что она может содержать либо экземпляр класса, либо значение null.

private function __construct(): Это приватный конструктор класса, который запрещает создание объектов этого класса извне класса.

public static function getInstance(): static: Это статический метод getInstance(), который возвращает единственный экземпляр класса. Метод объявлен с типом возвращаемого значения static, что означает, что он будет возвращать экземпляр класса, на котором он был вызван.

return static::$instance ?? static::$instance = new static();: В этой строке происходит проверка, создан ли уже экземпляр класса. Если экземпляр уже существует (не равен null), метод вернет существующий экземпляр. Если экземпляр еще не создан, метод создаст новый экземпляр с помощью оператора new static(), который создает экземпляр текущего класса, на котором был вызван метод, и присвоит его свойству $instance, чтобы сохранить его для будущих вызовов.

Таким образом, с использованием трейта TSingleton, любой класс, использующий этот трейт, будет обеспечивать доступ к единственному экземпляру класса через метод getInstance(). Это позволяет сделать класс "Одиночкой" и гарантировать, что у него будет только один экземпляр.
 */