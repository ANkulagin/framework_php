<?php


namespace wfm;


class Registry
{

    use TSingleton;

    protected static array $properties = [];

    public function setProperty($name, $value)
    {
        self::$properties[$name] = $value;
    }

    public function getProperty($name)
    {
        return self::$properties[$name] ?? null;
    }

    public function getProperties(): array
    {
        return self::$properties;
    }

}




/*
 * Данный класс Registry использует ранее определенный трейт TSingleton и реализует паттерн "Одиночка" для управления глобальным реестром свойств (переменных).

Разберем код подробнее:

namespace wfm;: Это пространство имен wfm, к которому относится данный класс.

class Registry: Объявление класса Registry.

use TSingleton;: Данный класс использует трейт TSingleton, чтобы обеспечить единственный экземпляр класса.

protected static array $properties = [];: Это статическое свойство $properties, которое хранит ассоциативный массив свойств (переменных).

public function setProperty($name, $value): Это публичный метод setProperty(), который используется для установки значения свойства (переменной) в реестре.

self::$properties[$name] = $value;: В этой строке происходит установка значения свойства с ключом $name в массиве $properties.

public function getProperty($name): Это публичный метод getProperty(), который используется для получения значения свойства (переменной) из реестра по его имени.

return self::$properties[$name] ?? null;: В этой строке метод возвращает значение свойства с ключом $name, если оно существует в массиве $properties, или null, если свойство не найдено.

public function getProperties(): array: Это публичный метод getProperties(), который возвращает все свойства из реестра в виде ассоциативного массива.

return self::$properties;: В этой строке метод возвращает все свойства, хранящиеся в массиве $properties.

Класс Registry представляет собой глобальный реестр свойств, который позволяет устанавливать и получать значения свойств по их именам в разных частях приложения. Благодаря использованию трейта TSingleton, можно быть уверенным, что в приложении существует только один экземпляр класса Registry, и все вызовы методов этого класса будут возвращать ссылку на один и тот же объект реестра свойств. Это обеспечивает централизованное хранение и доступ к глобальным данным, что может быть полезно для обмена информацией между различными компонентами приложения.
 */