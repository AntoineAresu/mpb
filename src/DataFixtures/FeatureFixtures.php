<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\EventListener\TraceableEntityListener;
use App\Tests\Factory\FeatureFactory;
use App\Tests\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class FeatureFixtures extends Fixture implements DependentFixtureInterface
{
    use DisableEntityListener;
    final public const FEATURE_FROM_BASIC_USER = 'feature_from_basic_user';
    final public const DRAFT_FROM_BASIC_USER = 'draft_from_basic_user';
    final public const FEATURE_FROM_TEAM_USER = 'feature_from_team_user';
    final public const DRAFT_FROM_TEAM_USER = 'draft_from_team_user';

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->disableEntityListener(TraceableEntityListener::class);
        FeatureFactory::createMany(3);
        FeatureFactory::createMany(3, ['draft' => true, 'title' => '']);
        FeatureFactory::createOne(['draft' => true, 'title' => self::DRAFT_FROM_BASIC_USER]);
        FeatureFactory::createOne(['draft' => false, 'title' => self::FEATURE_FROM_BASIC_USER]);
        FeatureFactory::createOne(['draft' => true, 'title' => self::DRAFT_FROM_TEAM_USER, 'user' => UserFactory::createOne(['roles' => [User::ROLE_TEAM]])]);
        FeatureFactory::createOne(['draft' => false, 'title' => self::FEATURE_FROM_TEAM_USER, 'user' => UserFactory::createOne(['roles' => [User::ROLE_TEAM]])]);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
