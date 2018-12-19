<?php
/**
 * @copyright CONTENT CONTROL GmbH, http://www.contentcontrol-berlin.de
 */

namespace midcom\datamanager\extension;

use Symfony\Component\Form\AbstractExtension;

/**
 * Experimental extension class
 */
class extension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadTypes()
    {
        return [
            new type\autocompleteType,
            new type\downloadsType,
            new type\codemirrorType,
            new type\imagesType,
            new type\jsdateType,
            new type\markdownType,
            new type\photoType,
            new type\radiocheckselectType,
            new type\subformType,
            new type\selectType,
            new type\tinymceType,
            new type\toolbarType,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadTypeExtensions()
    {
        return [
            new formExtension,
            new buttonExtension,
            new textExtension,
            new textareaExtension
        ];
    }
}
