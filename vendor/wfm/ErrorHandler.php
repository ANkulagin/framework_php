<?php


namespace wfm;


class ErrorHandler
{

    public function __construct()
    {

        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'errorHandler']);
        ob_start();
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $this->logError($errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            $this->logError($error['message'], $error['file'], $error['line']);
            ob_end_clean();
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        } else {
            ob_end_flush();
        }
    }

    public function exceptionHandler(\Throwable $e)
    {
        $this->logError($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    protected function logError($message = '', $file = '', $line = '')
    {
        file_put_contents(
            LOGS . '/errors.log',
            "[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n=================\n",
            FILE_APPEND);
    }

    protected function displayError($errno, $errstr, $errfile, $errline, $responce = 500)
    {
        if ($responce == 0) {
            $responce = 404;
        }
        http_response_code($responce);
        if ($responce == 404 && !DEBUG) {
            require WWW . '/errors/404.php';
            die;
        }
        if (DEBUG) {
            require WWW . '/errors/development.php';
        } else {
            require WWW . '/errors/production.php';
        }
        die;
    }

}




//конструктор
/*
 * Данный код представляет конструктор класса ErrorHandler, который инициализирует обработчик ошибок и исключений. Давайте разберем подробнее, что делает каждая строка кода:

public function __construct(): Это конструктор класса ErrorHandler, который вызывается при создании нового объекта этого класса.

if (DEBUG) { error_reporting(-1); } else { error_reporting(0); }: Здесь происходит установка уровня вывода ошибок в зависимости от значения константы DEBUG. Если DEBUG равно true, то устанавливается уровень вывода ошибок на максимальный (-1), что означает, что все ошибки будут отображаться. Если DEBUG равно false, то устанавливается уровень вывода ошибок на минимальный (0), что означает, что ошибки не будут отображаться.

set_exception_handler([$this, 'exceptionHandler']);: Здесь устанавливается обработчик исключений для текущего объекта класса ErrorHandler. Когда возникает исключение, будет вызываться метод exceptionHandler этого объекта для его обработки.

set_error_handler([$this ,'errorHandler']);: Здесь устанавливается обработчик ошибок для текущего объекта класса ErrorHandler. Когда возникает ошибка, будет вызываться метод errorHandler этого объекта для его обработки.

ob_start();: Здесь запускается буферизация вывода. Это необходимо для того, чтобы можно было перехватывать вывод и обрабатывать ошибки, которые возникают при выводе.

register_shutdown_function([$this,'fatalErrorHandler']);: Здесь регистрируется функция обработчика фатальных ошибок, которая будет вызываться при завершении работы скрипта. Это нужно для того, чтобы можно было обработать фатальные ошибки, которые не были обработаны обычными обработчиками ошибок.

Таким образом, конструктор ErrorHandler настраивает обработку ошибок и исключений для текущего объекта класса ErrorHandler, устанавливает уровень вывода ошибок в зависимости от значения константы DEBUG, запускает буферизацию вывода и регистрирует обработчик фатальных ошибок.
 */
// exceptionHandler, logErrors и displayError
/*
 *Давайте разберем каждую строку кода методов exceptionHandler, logErrors и displayError:

public function exceptionHandler(\Throwable $e): Это метод exceptionHandler, который принимает объект исключения \Throwable $e в качестве аргумента. Он вызывается при возникновении исключения и используется для обработки исключений.

$this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());: Здесь вызывается метод logErrors, который записывает информацию об исключении в лог-файл.

$this->displayError("Исключение", $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());: Здесь вызывается метод displayError, который отображает информацию об исключении и выводит соответствующую страницу ошибки.

protected function logErrors($message = "", $file = '', $line =''): Это метод logErrors, который записывает информацию об ошибке в лог-файл.

file_put_contents(LOGS . '/errors.log', "[" .date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n===========\n", FILE_APPEND);: Здесь происходит запись информации об ошибке в лог-файл. В этой строке используется функция file_put_contents, которая записывает содержимое в файл. В данном случае, записывается текущая дата и время, текст ошибки, путь к файлу, в котором произошла ошибка, и номер строки, на которой ошибка произошла.

protected function displayError($errno, $errstr, $errfile, $errline, $response = 500): Это метод displayError, который отображает информацию об ошибке и выводит соответствующую страницу ошибки.

if ($response == 0){ $response = 404; }: Если значение $response равно 0, то ошибка считается не найденной (404), и соответствующая страница ошибки будет выведена. Это может произойти, если в коде предполагается использование значения $response для определения статуса ошибки, но в данном случае оно равно 0.

http_response_code($response);: Устанавливается код ответа HTTP для ошибки. Этот код будет отправлен вместе с сообщением об ошибке клиенту.

if ($response == 404 && !DEBUG){ require WWW . '/errors/404.php'; die(); }: Если значение $response равно 404 и не установлена константа DEBUG, то будет выведена страница ошибки 404.php.

if (DEBUG){ require WWW .'/errors/development.php'; } else { require WWW . '/errors/production.php'; }: Если установлена константа DEBUG, то будет выведена страница ошибки development.php, иначе будет выведена страница ошибки production.php.

die();: Завершение работы скрипта после вывода страницы ошибки.
 */
//errorHandler и fatalErrorHandler:
/*
 *Давайте разберем каждую строку методов errorHandler и fatalErrorHandler:

Метод errorHandler:

public function errorHandler($errno, $errstr, $errfile, $errline): Это метод errorHandler, который вызывается при возникновении ошибки и принимает аргументы: $errno - код ошибки, $errstr - текст ошибки, $errfile - файл, в котором произошла ошибка, $errline - номер строки, на которой ошибка произошла.

$this->logErrors($errstr, $errfile, $errline);: Здесь вызывается метод logErrors, который записывает информацию об ошибке в лог-файл.

$this->displayError($errno, $errstr, $errfile, $errline);: Здесь вызывается метод displayError, который отображает информацию об ошибке и выводит соответствующую страницу ошибки.

Метод fatalErrorHandler:

public function fatalErrorHandler(): Это метод fatalErrorHandler, который вызывается в конце скрипта (после завершения выполнения), если возникла фатальная ошибка (E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR).

$error = error_get_last();: Получение последней возникшей ошибки в формате массива.

if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)): Проверка, что массив с ошибкой не пустой и тип ошибки соответствует фатальным ошибкам (E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR).

$this->logErrors($error['message'], $error['file'], $error['line']);: Здесь вызывается метод logErrors, который записывает информацию об ошибке в лог-файл.

ob_end_clean();: Очистка и отключение буфера вывода, чтобы не выводить ненужные данные.

$this->displayError($error['type'], $error['message'], $error['file'], $error['line']);: Здесь вызывается метод displayError, который отображает информацию об ошибке и выводит соответствующую страницу ошибки, связанную с фатальной ошибкой.

else { ob_end_flush(); }: Если нет фатальных ошибок, то происходит отправка (вывод) буфера и его отключение, чтобы правильно завершить работу скрипта.

Оба метода errorHandler и fatalErrorHandler предназначены для обработки и отображения ошибок в приложении, а метод logErrors используется для записи информации об ошибках в лог-файл.
 */
//Методы errorHandler и exceptionHandler различаются в обработке различных типов ошибок:
/*
 * Методы errorHandler и exceptionHandler различаются в обработке различных типов ошибок:

errorHandler: Этот метод предназначен для обработки обычных ошибок (например, E_NOTICE, E_WARNING, E_ERROR), которые не являются исключительными ситуациями, но все равно требуют обработки и логирования. Он вызывается при возникновении ошибки с помощью функции set_error_handler, которая устанавливает пользовательскую функцию обработки ошибок. Метод errorHandler логирует информацию об ошибке и выводит соответствующую страницу с сообщением об ошибке для пользователя. Он используется для некритических ошибок и не прерывает работу приложения.

exceptionHandler: Этот метод предназначен для обработки исключительных ситуаций (исключений), которые могут возникнуть в процессе выполнения кода. Он вызывается при возникновении исключения с помощью функции set_exception_handler, которая устанавливает пользовательскую функцию обработки исключений. Метод exceptionHandler также логирует информацию об исключении и выводит соответствующую страницу с сообщением об ошибке для пользователя. Исключения обычно являются более серьезными ошибками, которые могут привести к непредсказуемому поведению приложения, и их обработка обычно включает восстановление работы программы или выполнение альтернативных действий.

Кратко говоря, метод errorHandler обрабатывает обычные ошибки, а метод exceptionHandler обрабатывает исключения, которые могут возникнуть в процессе выполнения кода. Оба метода выполняют логирование ошибок и вывод соответствующей информации пользователю, но для разных типов ошибок.
 */