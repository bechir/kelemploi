<?php

namespace App\Form\DataTransformer;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * This data transformer is used to translate the array of skills into a comma separated format
 * that can be displayed and managed by Bootstrap-skillsinput js plugin (and back on submit).
 *
 * See https://symfony.com/doc/current/form/data_transformers.html
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 * @author Jonathan Boyer <contact@grafikart.fr>
 */
class SkillArrayToStringTransformer implements DataTransformerInterface
{
    private $skills;

    public function __construct(SkillRepository $skills)
    {
        $this->skills = $skills;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($skills): string
    {
        // The value received is an array of Skill objects generated with
        // Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::transform()
        // The value returned is a string that concatenates the string representation of those objects

        /* @var Skill[] $skills */
        return implode(',', $skills);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($string): array
    {
        if ('' === $string || null === $string) {
            return [];
        }

        $names = array_filter(array_unique(array_map('trim', explode(',', $string))));

        // Get the current skills and find the new ones that should be created.
        $skills = $this->skills->findBy([
            'name' => $names,
        ]);
        $newNames = array_diff($names, $skills);
        foreach ($newNames as $name) {
            $tag = new Skill();
            $tag->setName($name);
            $skills[] = $tag;

            // There's no need to persist these new skills because Doctrine does that automatically
            // thanks to the cascade={"persist"} option in the App\Entity\Post::$skills property.
        }

        // Return an array of skills to transform them back into a Doctrine Collection.
        // See Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::reverseTransform()
        return $skills;
    }
}
