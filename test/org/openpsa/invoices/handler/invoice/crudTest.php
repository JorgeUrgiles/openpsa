<?php
/**
 * @package openpsa.test
 * @author CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @copyright CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

namespace test\org\openpsa\invoices\handler\invoice;

use openpsa_testcase;
use org_openpsa_invoices_invoice_dba;
use midcom;

/**
 * OpenPSA testcase
 *
 * @package openpsa.test
 */
class crudTest extends openpsa_testcase
{
    protected static $_person;
    protected static $_invoice;

    public static function setUpBeforeClass() : void
    {
        self::$_person = self::create_user(true);
        self::$_invoice = self::create_class_object(org_openpsa_invoices_invoice_dba::class, [
            'customerContact' => self::$_person->id
        ]);
    }

    public function testHandler_create()
    {
        midcom::get()->auth->request_sudo('org.openpsa.invoices');

        $data = $this->run_handler('org.openpsa.invoices', ['invoice', 'new']);
        $this->assertEquals('invoice_new_nocustomer', $data['handler_id']);

        $data = $this->run_handler('org.openpsa.invoices', ['invoice', 'new', self::$_person->guid]);
        $this->assertEquals('invoice_new', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_edit()
    {
        midcom::get()->auth->request_sudo('org.openpsa.invoices');

        $data = $this->run_handler('org.openpsa.invoices', ['invoice', 'edit', self::$_invoice->guid]);
        $this->assertEquals('invoice_edit', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_delete()
    {
        midcom::get()->auth->request_sudo('org.openpsa.invoices');

        $data = $this->run_handler('org.openpsa.invoices', ['invoice', 'delete', self::$_invoice->guid]);
        $this->assertEquals('invoice_delete', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_read()
    {
        midcom::get()->auth->request_sudo('org.openpsa.invoices');

        $data = $this->run_handler('org.openpsa.invoices', ['invoice', self::$_invoice->guid]);
        $this->assertEquals('invoice', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }
}
