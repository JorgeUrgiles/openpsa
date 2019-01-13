<?php
/**
 * @package midcom.compat
 * @author CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @copyright CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Support for interactions with environment
 *
 * @package midcom.compat
 */
class midcom_compat_environment
{
    private static $_headers = [];

    private static $_implementation;

    public function __construct()
    {
        if (   php_sapi_name() != 'cli'
            || !empty($_SERVER['REMOTE_ADDR'])) {
            $this->_httpd_setup();
        }
    }

    public static function get()
    {
        return self::$_implementation;
    }

    public static function initialize()
    {
        self::$_implementation = new static;
    }

    private function _httpd_setup()
    {
        /*
         * Second, make sure the URLs not having query string (or midcom-xxx- -method signature)
         * have trailing slash or some extension in the "filename".
         *
         * This makes life much, much better when making static copies for whatever reason
         *
         * 2008-09-26: Now also rewrites urls ending in .html to end with trailing slash.
         */
        $redirect_test_uri = (string)$_SERVER['REQUEST_URI'];
        if (   !isset($_SERVER['MIDCOM_COMPAT_REDIR'])
            || (bool)$_SERVER['MIDCOM_COMPAT_REDIR'] !== false) {
            $redirect_test_uri = preg_replace('/\.html$/', '', $redirect_test_uri);
        }
        if (   !preg_match('%\?|/$|midcom-.+-|/.*\.[^/]+$%', $redirect_test_uri)
            && (empty($_POST))) {
            $response = new RedirectResponse($redirect_test_uri . '/', 301);
            $response->send();
            $this->stop_request();
        }
    }

    public function header($string, $replace = true, $http_response_code = null)
    {
        if (!defined('OPENPSA2_UNITTEST_RUN')) {
            header($string, $replace, $http_response_code);
        } else {
            self::$_headers[] = [
                'value' => $string,
                'replace' => $replace,
                'http_response_code' => $http_response_code
            ];
        }
    }

    public function stop_request($message = '')
    {
        if (!defined('OPENPSA2_UNITTEST_RUN')) {
            exit($message);
        }
    }

    public function headers_sent()
    {
        if (!defined('OPENPSA2_UNITTEST_RUN')) {
            return headers_sent();
        }
    }

    public static function flush_registered_headers()
    {
        $headers = self::$_headers;
        self::$_headers = [];
        return $headers;
    }
}
