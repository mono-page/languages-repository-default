<?php declare(strict_types=1);

namespace Monopage\Languages\Repositories;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Generator;
use Monopage\Domain\Attributes\IdentifierValue;
use Monopage\Domain\Exceptions\DomainException;
use Monopage\Languages\Entities\Language;
use Monopage\Languages\Repositories\Interfaces\LanguageRepositoryInterface;

class LanguageDefaultRepository implements LanguageRepositoryInterface
{
    protected EntityManager $entityManager;
    protected EntityRepository $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Language::class);
    }

    /**
     * @param IdentifierValue $id
     *
     * @return Language|object|null
     */
    public function get($id): ?Language
    {
        return $this->repository->find((string)$id);
    }

    /**
     * @param Language $language
     *
     * @throws DomainException
     */
    public function add(Language $language): void
    {
        try {
            $this->entityManager->persist($language);
            $this->entityManager->flush();
        } catch (OptimisticLockException | ORMException $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param Language $language
     *
     * @throws DomainException
     */
    public function update(Language $language): void
    {
        try {
            $this->entityManager->persist($language);
            $this->entityManager->flush();
        } catch (OptimisticLockException | ORMException $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param Language $language
     *
     * @throws DomainException
     */
    public function remove(Language $language): void
    {
        try {
            $this->entityManager->remove($language);
            $this->entityManager->flush();
        } catch (OptimisticLockException | ORMException $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param Criteria $criteria
     *
     * @return Generator|Language[]
     *
     * @throws DomainException
     */
    public function match(Criteria $criteria): Generator
    {
        try {
            $iterator = $this->repository->matching($criteria)->getIterator();
        } catch (Exception $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }

        foreach ($iterator as $index => $entity) {

            yield $index => $entity;
        }

        $this->repository->clear();
    }
}
