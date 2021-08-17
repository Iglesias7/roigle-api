<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param array<string> $criteria
     *
     * @return User[]
     */
    public function findByCriteria(array $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        $filterByEmail = array_key_exists('email', $criteria);
        $filterByPassword = array_key_exists('password', $criteria);
        $filterByFriends = array_key_exists('friend', $criteria);
        $filterByAsks = array_key_exists('ask', $criteria);
        $filterByReceived = array_key_exists('received', $criteria);

        if($filterByFriends) {
            return $this->find(json_encode($criteria['friend']))->getFollowers();
        } else if($filterByAsks) {
            return $this->find(json_encode($criteria['ask']))->getFriendsSend();
        } else if($filterByReceived) {
            $id = $criteria['received'];
            $user = $this->createQueryBuilder('u')
                ->andWhere('u.id = :received')
                ->setParameter('received', $id)
                ->getQuery()
                ->getResult();
            var_dump($user);
            return [];
        } else {
            if ($filterByEmail && $filterByPassword) {
                $email = $criteria['email'];
                $user = $this->findOneBy(['email' => $criteria['email']]);

                if($this->userPasswordEncoder->isPasswordValid($user, $criteria['password'])) {
                    $queryBuilder
                        ->andWhere('u.email = :email')
                        ->setParameter('email', $email);
                } else {
                    return [];
                }
            } else if ($filterByEmail) {
                $email = $criteria['email'];

                $queryBuilder
                    ->andWhere('u.email = :email')
                    ->setParameter('email', $email);
            }

            return $queryBuilder->getQuery()->getResult();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

     /**
      * @return User[] Returns an array of User objects
      */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneByEmail($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($email): ?User
    {
        return
            $this->createQueryBuilder('u')
                ->andWhere('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult();
    }
}
