<?php
/**
 * @package openpsa.test
 * @author CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @copyright CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

namespace test\org\openpsa\invoices\handler;

use midcom_db_person;
use openpsa_testcase;
use midcom;
/**
 * OpenPSA testcase
 *
 * @package openpsa.test
 */
class scheduledTest extends openpsa_testcase
{
    protected static midcom_db_person $_person;

    public static function setUpBeforeClass() : void
    {
        self::$_person = self::create_user(true);
    }

    public function testHandler_list()
    {
        midcom::get()->auth->request_sudo('org.openpsa.invoices');

        $data = $this->run_handler('org.openpsa.invoices', ['scheduled']);
        $this->assertEquals('list_scheduled', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }
}
