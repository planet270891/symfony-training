<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Contact::class);
        $this->manager = $manager;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Contact $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Contact $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function saveContact($name, $email, $phone = null, $website = null, $address = null){
        $contact = new Contact();
        $contact
            ->setName($name)
            ->setEmail($email)
            ->setPhone($phone)
            ->setWebsite($website)
            ->setAddress($address)
            ->setCreatedAt(new \DateTimeImmutable('now'));

        $this->manager->persist($contact);
        $this->manager->flush();
        return array(
            "id"=> $contact->getId(),
            "name"=> $contact->getName(),
            "email"=> $contact->getEmail(),
            "phone"=> $contact->getPhone(),
            "website"=> $contact->getWebsite(),
            "address"=> $contact->getAddress(),
            "created_at"=> $contact->getCreatedAt(),
            "updated_at"=> $contact->getUpdatedAt(),
            "deleted_at"=> $contact->getDeletedAt()
        );
    }

    public function updateContact(Contact $contact, $data)
    {
        empty($data['name']) ?  true : $contact->setName($data['name']);
        empty($data['email']) ? true : $contact->setEmail($data['email']);
        empty($data['phone']) ? true : $contact->setPhone($data['phone']);
        empty($data['website']) ? true : $contact->setWebsite($data['website']);
        empty($data['address']) ? true : $contact->setAddress($data['address']);

        $contact->setUpdatedAt(new \DateTimeImmutable('now'));
        $this->manager->flush();

        return array(
            "id"=> $contact->getId(),
            "name"=> $contact->getName(),
            "email"=> $contact->getEmail(),
            "phone"=> $contact->getPhone(),
            "website"=> $contact->getWebsite(),
            "address"=> $contact->getAddress(),
            "created_at"=> $contact->getCreatedAt(),
            "updated_at"=> $contact->getUpdatedAt(),
            "deleted_at"=> $contact->getDeletedAt()
        );
    }

    public function removeContact(Contact $contact){
        $this->manager->remove($contact);
        $this->manager->flush();
    }

    // /**
    //  * @return Contact[] Returns an array of Contact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contact
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
