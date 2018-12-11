<?php
/**
 * @copyright CONTENT CONTROL GmbH, http://www.contentcontrol-berlin.de
 */

namespace midcom\datamanager\extension\type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType as base;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use midcom\datamanager\extension\helper;
use midcom\datamanager\validation\pattern as validator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormBuilderInterface;
use midcom\datamanager\extension\subscriber\purifySubscriber;

/**
 * Experimental textarea type
 */
class textareaType extends base
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('constraints', []);
        $resolver->setNormalizer('type_config', function (Options $options, $value) {
            $type_defaults = [
                'output_mode' => 'html',
                'specialchars_quotes' => ENT_QUOTES,
                'specialchars_charset' => 'UTF-8',
                'forbidden_patterns' => [],
                'maxlength' => 0,
                'purify' => false,
                'purify_config' => []
            ];
            return helper::resolve_options($type_defaults, $value);
        });
        $resolver->setNormalizer('attr', function (Options $options, $value) {
            if (!empty($options['widget_config']['height'])) {
                $value['rows'] = $options['widget_config']['height'];
            }
            return $value;
        });
        $resolver->setNormalizer('constraints', function (Options $options, $value) {
            if (!empty($options['type_config']['forbidden_patterns'])) {
                $value[] = new validator(['forbidden_patterns' => $options['type_config']['forbidden_patterns']]);
            }
            if (!empty($options['type_config']['maxlength'])) {
                $value[] = new Length(['max' => $options['type_config']['maxlength']]);
            }
            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        if ($options['type_config']['purify']) {
            $builder->addEventSubscriber(new purifySubscriber($options['type_config']['purify_config']));
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['output_mode'] = $options['type_config']['output_mode'];
    }
}
