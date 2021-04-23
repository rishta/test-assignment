<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\PhoneBookEntryShare;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

/**
 * Class: PhoneBookEntryShareOwnerExtension
 *
 * @see QueryCollectionExtensionInterface
 * @see QueryItemExtensionInterface
 * @final
 */
final class PhoneBookEntryShareOwnerExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * applyToCollection
     *
     * @param QueryBuilder $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string $resourceClass
     * @param string $operationName
     */
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void
    {
        if ($resourceClass !== PhoneBookEntryShare::class) {
            return;
        }

        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * applyToItem
     *
     * @param QueryBuilder $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string $resourceClass
     * @param array $identifiers
     * @param string $operationName
     * @param array $context
     */
    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * addWhere
     *
     * @param QueryBuilder $queryBuilder
     * @param string $resourceClass
     */
    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (PhoneBookEntryShare::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN') || null === $user = $this->security->getUser()) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.share = :current_user', $rootAlias));
        $queryBuilder->setParameter('current_user', $user->getId());
    }
}

