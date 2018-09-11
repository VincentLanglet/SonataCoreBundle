<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\CoreBundle\Test;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Version;
use PHPUnit\Framework\TestCase;

final class EntityManagerMockFactory
{
    public static function create(TestCase $test, \Closure $queryBuilderCallback, $fields): EntityManagerInterface
    {
        $query = $test->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()->getMock();
        $query->expects($test->any())->method('execute')->will($test->returnValue(true));

        if (Version::compare('2.5.0') < 1) {
            $entityManager = $test->getMockBuilder(EntityManagerInterface::class)->getMock();
            $queryBuilder = $test->getMockBuilder(QueryBuilder::class)->setConstructorArgs([$entityManager])->getMock();
        } else {
            $queryBuilder = $test->getMockBuilder(QueryBuilder::class)->disableOriginalConstructor()->getMock();
        }

        $queryBuilder->expects($test->any())->method('select')->will($test->returnValue($queryBuilder));
        $queryBuilder->expects($test->any())->method('getQuery')->will($test->returnValue($query));
        $queryBuilder->expects($test->any())->method('where')->will($test->returnValue($queryBuilder));
        $queryBuilder->expects($test->any())->method('orderBy')->will($test->returnValue($queryBuilder));
        $queryBuilder->expects($test->any())->method('andWhere')->will($test->returnValue($queryBuilder));
        $queryBuilder->expects($test->any())->method('leftJoin')->will($test->returnValue($queryBuilder));

        $queryBuilderCallback($queryBuilder);

        $repository = $test->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $repository->expects($test->any())->method('createQueryBuilder')->will($test->returnValue($queryBuilder));

        $metadata = $test->getMockBuilder(ClassMetadata::class)->getMock();
        $metadata->expects($test->any())->method('getFieldNames')->will($test->returnValue($fields));
        $metadata->expects($test->any())->method('getName')->will($test->returnValue('className'));

        $entityManager = $test->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $entityManager->expects($test->any())->method('getRepository')->will($test->returnValue($repository));
        $entityManager->expects($test->any())->method('getClassMetadata')->will($test->returnValue($metadata));

        return $entityManager;
    }
}
