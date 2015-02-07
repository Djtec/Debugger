<?php

namespace Djtec;


class Debugger {

    const INDENT = 4;


    /**
     * @param $var
     * @param bool $css Use css in Debugger class
     * @param bool $echo
     * @return string
     */
    public static function run($var, $css = true, $echo = false)
    {
        $trace = debug_backtrace()[0];

        $file = trim($trace['file'], '/');
        $line = $trace['line'];

        if ($css) {
            static::css();
        }

        $html = '<div class="debug"><div class="header">Debug</div><pre>';
        $html .= self::var_dump_plain($var);
        $html .= '</pre><div class="info"><div class="left">File: '.$file.'</div><div class="right">Line: '.$line.'</div></div></div>';

        if ($echo) {
            echo $html;
        } else {
            return $html;
        }
    }

    protected static function var_dump_plain($var)
    {
        $html = '';

        if (is_bool($var)) {
            $html .= '<span style="color:#79e3e1;">bool</span><span style="color:#999;">(</span><strong>' . (($var) ? 'true' : 'false') . '</strong><span style="color:#999;">)</span>';
        } elseif (is_int($var)) {
            $html .= '<span style="color:#79e3e1;">int</span><span style="color:#999;">(</span><strong>' . $var . '</strong><span style="color:#999;">)</span>';
        } elseif (is_float($var)) {
            $html .= '<span style="color:#79e3e1;">float</span><span style="color:#999;">(</span><strong>' . $var . '</strong><span style="color:#999;">)</span>';
        } elseif (is_string($var)) {
            $html .= '<span style="color:#79e3e1;">string</span><span style="color:#999;">(</span>' . strlen($var) . '<span
style="color:#999;">)</span> <strong>"' . self::htmlentities($var) . '"</strong>';
        } elseif (is_null($var)) {
            $html .= '<strong><span style="color:#fdff62;">NULL</span></strong>';
        } elseif (is_resource($var)) {
            $html .= '<span style="color:#79e3e1;">resource</span>("' . get_resource_type($var) . '") <strong>"' . $var . '"</strong>';
        } elseif (is_array($var)) {
            $html .= '<span style="color:#e16ca1;">array</span><span style="color:#999;">(</span><strong>' . count
                ($var) . '</strong><span
style="color:#999;">)</span>';

            $html .= self::is_array($var);
        } elseif (is_object($var)) {
            $html .= '<span style="color:#e16ca1;">object</span><span style="color:#999;">(</span><span style="color: #fdff62">' . get_class($var) . '</span><span
style="color:#999;">)</span>';

            $html .= self::is_object($var);
        }

        return $html;
    }

    protected static function is_array($array)
    {
        $html = ' <span><br />[<br />';

        $indent = static::INDENT;
        $longest_key = 0;

        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $longest_key = max($longest_key, strlen($key) + 2);
            } else {
                $longest_key = max($longest_key, strlen($key));
            }
        }

        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $html .= str_repeat(' ', $indent) . str_pad($key, $longest_key, ' ');
            } else {
                $html .= str_repeat(' ', $indent) . str_pad('"' . self::htmlentities($key) . '"', $longest_key, ' ');
            }

            $html .= ' => ';
            $value = explode('<br />', self::var_dump_plain($value));

            foreach ($value as $line => $val) {
                if ($line != 0) {
                    $value[$line] = str_repeat(' ', $indent * 2) . $val;
                }
            }

            $html .= implode('<br />', $value) . '<br />';
        }

        $html .= ']</span>';

        return $html;
    }

    protected static function is_object($object)
    {
        $html = ' <span><br />[<br />';

        $varArray = (array) $object;
        $indent = static::INDENT;
        $longest_key = 0;

        foreach ($varArray as $key => $value) {
            if (substr($key, 0, 2) == "\0*") {
                unset($varArray[$key]);
                $key = 'protected:' . substr($key, 3);
                $varArray[$key] = $value;
            } elseif (substr($key, 0, 1) == "\0") {
                unset($varArray[$key]);
                $key = 'private:' . substr($key, 1, strpos(substr($key, 1), "\0")) . ':' . substr($key, strpos(substr($key, 1), "\0") + 2);
                $varArray[$key] = $value;
            }

            if (is_string($key)) {
                $longest_key = max($longest_key, strlen($key) + 2);
            } else {
                $longest_key = max($longest_key, strlen($key));
            }
        }

        foreach ($varArray as $key => $value) {
            if (is_numeric($key)) {
                $html .= str_repeat(' ', $indent) . str_pad($key, $longest_key, ' ');
            } else {
                $html .= str_repeat(' ', $indent) . str_pad('"' . self::htmlentities($key) . '"', $longest_key, ' ');
            }

            $html .= ' => ';
            $value = explode('<br />', self::var_dump_plain($value));

            foreach ($value as $line => $val) {
                if ($line != 0) {
                    $value[$line] = str_repeat(' ', $indent * 2) . $val;
                }
            }

            $html .= implode('<br />', $value) . '<br />';
        }

        $html .= ']</span>';

        return $html;
    }

    protected static function mbInternalEncoding($encoding = null)
    {
        if (function_exists('mb_internal_encoding')) {
            return $encoding ? mb_internal_encoding($encoding) : mb_internal_encoding();
        }

        return 'UTF-8';
    }

    protected static function htmlentities($string)
    {
        return htmlentities($string, ENT_QUOTES, self::mbInternalEncoding());
    }

    protected static function css()
    {
        $style = file_get_contents(dirname(__DIR__).'/assets/debugger.css');

        echo '<style type="text/css">'.$style.'</style>';
    }

}