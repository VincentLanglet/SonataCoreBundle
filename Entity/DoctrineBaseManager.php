<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sonata\CoreBundle\Model\BaseManager;
use Sonata\CoreBundle\Model\ManagerInterface;

/**
 * Class BaseManager.
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
abstract class DoctrineBaseManager extends BaseManager
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return $this->om->getConnection();
    }
}
