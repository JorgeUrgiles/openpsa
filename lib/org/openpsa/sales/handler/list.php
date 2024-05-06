<?php
/**
 * @package org.openpsa.sales
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

use midcom\grid\grid;

/**
 * Sales project list handler
 *
 * @package org.openpsa.sales
 */
class org_openpsa_sales_handler_list extends midcom_baseclasses_components_handler
{
    public function _handler_list(string $handler_id, array $args, array &$data)
    {
        // Locate Contacts node for linking
        $siteconfig = org_openpsa_core_siteconfig::get_instance();
        $data['contacts_url'] = $siteconfig->get_node_full_url('org.openpsa.contacts');

        $qb = org_openpsa_sales_salesproject_dba::new_query_builder();

        if ($handler_id == 'list_customer') {
            $this->_add_customer_constraint($args[0], $qb);
            $data['mode'] = 'customer';
            $data['list_title'] = sprintf($this->_l10n->get('salesprojects with %s'), $data['customer']->get_label());
            $this->add_breadcrumb("", $data['list_title']);
        } else {
            $data['mode'] = $this->get_list_mode($args);
            $this->_add_state_constraint($data['mode'], $qb);
            $data['list_title'] = $this->_l10n->get('salesprojects ' . $data['mode']);
            $this->set_active_leaf($this->_topic->id . ':' . $data['mode']);
        }

        $data['salesprojects'] = $qb->execute();
        // TODO: Filtering

        $data['grid'] = new grid($data['mode'] . '_salesprojects_grid', 'local');
        midcom::get()->head->add_jsfile(MIDCOM_STATIC_URL . '/midcom.grid/FileSaver.js');

        $this->add_toolbar_buttons();

        return $this->show('show-salesproject-grid');
    }

    private function get_list_mode(array $args) : string
    {
        $person = midcom::get()->auth->user->get_storage();
        $mode = $person->get_parameter($this->_component, 'list_mode');
        if (!empty($args[0])) {
            if ($mode !== $args[0]) {
                $person->set_parameter($this->_component, 'list_mode', $args[0]);
            }
            return $args[0];
        }
        return $mode ?: 'active';
    }

    private function add_toolbar_buttons()
    {
        $create_url = 'salesproject/new/';

        if (!empty($this->_request_data['customer'])) {
            $create_url .= $this->_request_data['customer']->guid . '/';

            if ($this->_request_data['contacts_url']) {
                $url_prefix = $this->_request_data['contacts_url'] . ($this->_request_data['customer'] instanceof org_openpsa_contacts_group_dba ? 'group' : 'person') . "/";
                $this->_view_toolbar->add_item([
                    MIDCOM_TOOLBAR_URL => $url_prefix . $this->_request_data['customer']->guid . '/',
                    MIDCOM_TOOLBAR_LABEL => $this->_l10n->get('go to customer'),
                    MIDCOM_TOOLBAR_GLYPHICON => 'user',
                ]);
            }
        }
        if (midcom::get()->auth->can_user_do('midgard:create', class: org_openpsa_sales_salesproject_dba::class)) {
            $workflow = $this->get_workflow('datamanager');
            $this->_view_toolbar->add_item($workflow->get_button($create_url, [
                MIDCOM_TOOLBAR_LABEL => $this->_l10n->get('create salesproject'),
                MIDCOM_TOOLBAR_GLYPHICON => 'book',
            ]));
        }
    }

    private function _add_state_constraint(string $state, midcom_core_query $qb)
    {
        $code = 'org_openpsa_sales_salesproject_dba::STATE_' . strtoupper($state);
        $qb->add_constraint('state', '=', constant($code));
    }

    private function _add_customer_constraint(string $guid, midcom_core_query $qb)
    {
        try {
            $this->_request_data['customer'] = new org_openpsa_contacts_group_dba($guid);
            $qb->add_constraint('customer', '=', $this->_request_data['customer']->id);
        } catch (midcom_error $e) {
            $this->_request_data['customer'] = new org_openpsa_contacts_person_dba($guid);
            $qb->add_constraint('customerContact', '=', $this->_request_data['customer']->id);
        }
    }
}
