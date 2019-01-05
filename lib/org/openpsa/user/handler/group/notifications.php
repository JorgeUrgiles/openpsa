<?php
/**
 * @package org.openpsa.user
 * @author CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @copyright CONTENT CONTROL http://www.contentcontrol-berlin.de/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * org.openpsa.contacts group handler class.
 *
 * @package org.openpsa.user
 */
class org_openpsa_user_handler_group_notifications extends midcom_baseclasses_components_handler
{
    /**
     * @param array $args The argument list.
     */
    public function _handler_notifications(array $args)
    {
        midcom::get()->auth->require_user_do('org.openpsa.user:manage', null, org_openpsa_user_interface::class);

        $group = new org_openpsa_contacts_group_dba($args[0]);
        $group->require_do('midgard:update');

        midcom::get()->head->set_pagetitle($this->_l10n->get("notification settings"));

        $notifier = new org_openpsa_notifications;
        $dm = $notifier
            ->load_datamanager()
            ->set_storage($group);

        $workflow = $this->get_workflow('datamanager', ['controller' => $dm->get_controller()]);
        return $workflow->run();
    }
}
