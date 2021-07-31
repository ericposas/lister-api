<?php

namespace PHPapp\ExtendedRepositories;

class ContactRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllContacts()
    {
        $contacts = $this->getEntityManager()->getRepository(\PHPapp\Entity\Contact::class)->findAll();
        $data = array();
        
        foreach ($contacts as $idx => $contact)
        {
            $data[$idx]["email"] = $contact->getEmail();
            $data[$idx]["phone"] = $contact->getPhone();
        }
        
        return $data;
    }
    
    public function getSingleContactByUserId(int $id)
    {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select("c", "u")
                ->from("\PHPapp\Entity\Contact", "c")
                ->join("c.user", "u")
                ->where("u.id = {$id}")
                ->getQuery()
                ->getArrayResult();
        
        $data = array();
        foreach ($query as $idx => $entry)
        {
            $data[] = array(
                "name" => $entry["user"]["name"],
                "mail" => $entry["email"],
                "tel" => $entry["phone"],
            );
        }
        
        return $data[0];
    }
}