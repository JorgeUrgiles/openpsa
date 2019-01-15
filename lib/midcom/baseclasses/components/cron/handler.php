<?php
/**
 * @package midcom.baseclasses
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * This is the base class used for all jobs run by MidCOM CRON.
 *
 * It gives you an easy to use way of building cron jobs. You should rely only on
 * the two event handlers _on_initialize and execute, which are called by the
 * cron service.
 *
 * See the main cron service class for details.
 *
 * @see midcom_services_cron
 * @package midcom.baseclasses
 */
abstract class midcom_baseclasses_components_cron_handler extends midcom_baseclasses_components_base
{
    /**
     * Initialize the cron job. Before calling the on_initialize callback, it prepares
     * the instance with various configuration variables
     *
     * @param array $config The full cron job configuration data.
     */
    public function initialize(array $config)
    {
        $this->_component = $config['component'];

        return $this->_on_initialize();
    }

    /**
     * This callback is executed immediately after object construction. You can initialize your class here.
     * If you return false here, the handler is not executed, the system skips it.
     *
     * All class members are already initialized when this event handler is called.
     *
     * @return boolean Returns true, if initialization was successful, false if anything went wrong.
     */
    public function _on_initialize()
    {
        return true;
    }

    /**
     * This is the actual handler operation, it is called only after successful operation.
     * You should use the print_error() helper of this class in case you need to notify
     * the user of any errors. As long as everything goes fine, you should not print anything
     * to avoid needless cron mailings.
     */
    abstract public function execute();

    /**
     * Echo the error message to the client, automatically appending
     * the classname to the prefix. Passed messages are also written to the error log.
     *
     * @param string $message The error message to print.
     * @param mixed $var A variable you want to print, if any.
     */
    public function print_error($message, $var = null)
    {
        $class = get_class($this);
        echo "ERROR ({$class}): {$message}\n";
        debug_add($message, MIDCOM_LOG_ERROR);
        if ($var !== null) {
            debug_print_r('Passed argument: ', $var);
        }
    }
}
