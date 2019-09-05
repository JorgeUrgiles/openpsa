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
class net_nemein_wiki_handler_feedTest extends openpsa_testcase
{
    protected static $_topic;

    public static function setUpBeforeClass()
    {
        $topic_attributes = [
            'component' => 'net.nemein.wiki',
            'name' => __CLASS__ . time()
        ];
        self::$_topic = self::create_class_object(midcom_db_topic::class, $topic_attributes);
    }

    public function testHandler_rss()
    {
        $data = $this->run_handler(self::$_topic, ['rss.xml']);
        $this->assertEquals('rss', $data['handler_id']);
    }
}
