<?php
/**
 * @package openpsa.test
 * @author CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @copyright CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

namespace test\midgard\admin\asgard\handler\component;

use openpsa_testcase;
use midcom;
use midcom_db_topic;

/**
 * OpenPSA testcase
 *
 * @package openpsa.test
 */
class configurationTest extends openpsa_testcase
{
    public function testHandler_view()
    {
        $this->create_user(true);
        midcom::get()->auth->request_sudo('midgard.admin.asgard');

        $data = $this->run_handler('net.nehmer.static', ['__mfa', 'asgard', 'components', 'configuration', 'net.nehmer.blog']);
        $this->assertEquals('components_configuration', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_edit()
    {
        $this->create_user(true);
        midcom::get()->auth->request_sudo('midgard.admin.asgard');

        $data = $this->run_handler('net.nehmer.static', ['__mfa', 'asgard', 'components', 'configuration', 'edit', 'net.nehmer.blog']);
        $this->assertEquals('components_configuration_edit', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_edit_folder()
    {
        $this->create_user(true);
        midcom::get()->auth->request_sudo('midgard.admin.asgard');

        $topic = $this->create_object(midcom_db_topic::class, ['component' => 'net.nehmer.blog']);

        $data = $this->run_handler('net.nehmer.static', ['__mfa', 'asgard', 'components', 'configuration', 'edit', 'net.nehmer.blog', $topic->guid]);
        $this->assertEquals('components_configuration_edit_folder', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }
}
