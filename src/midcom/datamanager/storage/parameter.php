<?php
/**
 * @copyright CONTENT CONTROL GmbH, http://www.contentcontrol-berlin.de
 */

namespace midcom\datamanager\storage;

/**
 * Experimental storage class
 */
class parameter extends delayed
{
    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $value = $this->object->get_parameter($this->config['storage']['domain'], $this->config['storage']['name']);

        if ($value === null && isset($this->config['default'])) {
            $value = $this->config['default'];
        }

        return $this->cast($value);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        // workaround for weird mgd API behavior where setting empty (i.e. deleting) a
        // nonexistent parameter returns false
        if (   in_array($this->value, [false, null, ""], true)
            && $this->load() === null) {
            return true;
        }

        return $this->object->set_parameter($this->config['storage']['domain'], $this->config['storage']['name'], $this->value);
    }
}