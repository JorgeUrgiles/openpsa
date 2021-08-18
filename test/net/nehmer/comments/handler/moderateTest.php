<?php
/**
 * @package openpsa.test
 * @author CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @copyright CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

namespace test\net\nehmer\comments\handler;

use openpsa_testcase;
use midcom;
use midcom_db_topic;
use net_nehmer_comments_comment;

/**
 * OpenPSA testcase
 *
 * @package openpsa.test
 */
class moderateTest extends openpsa_testcase
{
    public function testHandler_abuse()
    {
        $this->create_user(true);
        $topic = $this->create_object(midcom_db_topic::class);
        $attributes = ['objectguid' => $topic->guid];
        $comment = $this->create_object(net_nehmer_comments_comment::class, $attributes);

        midcom::get()->auth->request_sudo('net.nehmer.comments');

        $this->set_post_data([
            'return_url' => 'dummy'
        ]);

        $url = $this->run_relocate_handler('net.nehmer.comments', ['report', 'abuse', $comment->guid]);
        $this->assertEquals('dummy', $url);

        $comment->refresh();
        $this->assertEquals(net_nehmer_comments_comment::ABUSE, $comment->status);
        midcom::get()->auth->drop_sudo();
    }
}
