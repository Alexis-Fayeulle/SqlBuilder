<?php

use PHPUnit\Framework\TestCase;

class MyTestCase extends TestCase
{

    /*
        public function testAll()
        {
            $function_name = __FUNCTION__;
            $this_ = &$this;
            $prefix = 'test';

            $list_methods = array_filter(get_class_methods($this), static function($x) use ($function_name, $prefix) {
                return substr($x, 0, strlen($prefix)) === $prefix && strlen($x) > strlen($prefix) && $x !== $function_name;
            });

            $this->assertNotCatchError(static function() use ($this_, $list_methods) {
                foreach ($list_methods as $value) {
                    $this_->$value();
                }
            }, 'tout les tests ne passe pas');
        }
    */

    public function assertNotCatchError($callback, $message = '')
    {
        $res = true;

        try {
            $callback();
        } catch (\Throwable $th) {
            $res = false;
        }

        $this->assertTrue($res, 'Catch an error' . (empty($message) ? '' : ': ' . $message));
    }

    public function assertHaveMethod($object, $method, $message = '')
    {
        $this->assertTrue(method_exists($object, $method), 'Missing method' . (empty($message) ? '' : ': ' . $message));
    }

}
