<?php
/**
 * @package openpsa.test
 * @author CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @copyright CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * OpenPSA testcase
 *
 * @package openpsa.test
 */
class org_openpsa_directmarketing_handler_subscriberTest extends openpsa_testcase
{
    protected static $_person;

    public static function setUpBeforeClass()
    {
        self::$_person = self::create_user(true);
    }

    public function testHandler_list()
    {
        midcom::get()->auth->request_sudo('org.openpsa.directmarketing');

        $data = $this->run_handler('org.openpsa.directmarketing', ['campaign', 'list', self::$_person->guid]);
        $this->assertEquals('list_campaign_person', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_list_unsubscribe()
    {
        $helper = new openpsa_test_campaign_helper($this);
        $member = $helper->get_member(self::$_person);
        midcom::get()->auth->request_sudo('org.openpsa.directmarketing');

        $data = $this->run_handler('org.openpsa.directmarketing', ['campaign', 'unsubscribe', $member->guid]);
        $this->assertEquals('subscriber_unsubscribe', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_list_unsubscribe_all()
    {
        $helper = new openpsa_test_campaign_helper($this);
        $helper->get_member(self::$_person);
        midcom::get()->auth->request_sudo('org.openpsa.directmarketing');

        $data = $this->run_handler('org.openpsa.directmarketing', ['campaign', 'unsubscribe_all', self::$_person->guid]);
        $this->assertEquals('subscriber_unsubscribe_all', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_list_unsubscribe_all_future()
    {
        $helper = new openpsa_test_campaign_helper($this);
        $helper->get_member(self::$_person);
        midcom::get()->auth->request_sudo('org.openpsa.directmarketing');

        $data = $this->run_handler('org.openpsa.directmarketing', ['campaign', 'unsubscribe_all_future', self::$_person->guid, 'test']);
        $this->assertEquals('subscriber_unsubscribe_all_future', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }

    public function testHandler_list_unsubscribe_ajax()
    {
        $helper = new openpsa_test_campaign_helper($this);
        $member = $helper->get_member(self::$_person);
        midcom::get()->auth->request_sudo('org.openpsa.directmarketing');

        $data = $this->run_handler('org.openpsa.directmarketing', ['campaign', 'unsubscribe', 'ajax', $member->guid]);
        $this->assertEquals('subscriber_unsubscribe_ajax', $data['handler_id']);

        midcom::get()->auth->drop_sudo();
    }
}
