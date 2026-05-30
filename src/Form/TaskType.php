<?php

namespace App\Form;

use App\Entity\Task;
use App\Enum\TaskStatus;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;


class TaskType extends AbstractType
{
    public function __construct(TranslatorInterface $translator) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add(
                'title',
                null,
                [
                    'label' => t('task.title')
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => t('task.description')
                ]
            )
            ->add(
                'status',
                EnumType::class,
                [
                    'class' => TaskStatus::class,
                    'label' => t('task.status')

                ]
            )
            ->add('deadline', DateType::class, [
                'required' => false,
                'widget'   => 'single_text',
                'label' => t('task.deadline')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
