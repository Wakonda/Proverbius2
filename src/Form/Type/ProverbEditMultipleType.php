<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Tag;

use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ProverbEditMultipleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$locale = $options["locale"];

        $builder
		   ->add('tags', Select2EntityType::class, [
				'label' => 'admin.proverb.Tags',
				'multiple' => true,
				'remote_route' => 'tagadmin_gettagsbyajax',
				'class' => Tag::class,
				'remote_params' => ['locale' => $locale],
				'page_limit' => 10,
				'primary_key' => 'id',
				'text_property' => 'title',
				'allow_clear' => true,
				'delay' => 250,
				'cache' => true,
				'cache_timeout' => 60000, // if 'cache' is true
				'language' => $locale,
				'placeholder' => 'main.field.ChooseAnOption'
			])
            ->add('save', SubmitType::class, array('label' => 'admin.main.Save', 'attr' => array('class' => 'btn btn-success')));
    }

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			"locale" => null
		));
	}
	
    public function getName()
    {
        return 'proverb_edit_multiple';
    }
}