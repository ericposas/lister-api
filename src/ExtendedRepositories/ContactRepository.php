<?php

namespace PHPapp\ExtendedRepositories;

class ContactRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param string $id takes in the id of the Contact entity to delete from the database
     * @return bool returns true if delete op was successful
     */
    public function deleteContact(string $id)
    {
        $contact = $this->find($id);
        if (!$contact) {
            return false;
        }
        $user = $contact->getUser();
        try {
            if (isset($user)) {
                $contact->addUser(null);
                $user->addContact(null);
                $em->flush();
            }
            $em->remove($contact);
            $em->flush();
        } catch (Exception $ex) {
            throw new Exception("Could not delete User of id {$id}");
            return false;
        }
        
        return true;
    }
    
    /**
     * @param User $id takes in a User id int
     * @param requestBody $body request body with params passed in
     * @return array returns an array with a status string, either "Created new", "Changed", or "No user" and the $user
     */
    public function createOrUpdateContact(int $id, $body)
    {
        $user = $this->getEntityManager()
                ->getRepository(\PHPapp\Entity\User::class)
                ->find($id);
        
        $createdOrChanged = "";
        if (!$user) {
            $createdOrChanged = "No user";
            return [
                "user" => null,
                "status" => $createdOrChanged
            ];
        }
        try {
            $contactExist = $user->getContact();
            if (isset($contactExist)) {
                $createdOrChanged = "Changed";
                isset($body->email) ? $contactExist->setEmail($body->email) : null;
                isset($body->phone) ? $contactExist->setPhone($body->phone) : null;
            } else {
                $createdOrChanged = "Created new";
                $contact = new \PHPapp\Entity\Contact();
                isset($body->email) ? $contact->setEmail($body->email) : null;
                isset($body->phone) ? $contact->setPhone($body->phone) : null;
                $this->getEntityManager()->persist($contact);
                $contact->addUser($user);
                $user->addContact($contact);
            }
            $this->getEntityManager()
                    ->flush(); # flush changes to db aka close the transaction
        } catch (Exception $ex) {
            throw new Exception($ex);
        }
        return [
            "user" => $user,
            "status" => $createdOrChanged
        ];
    }

    public function getAllContacts()
    {
        $contacts = $this->getEntityManager()
                ->getRepository(\PHPapp\Entity\Contact::class)
                ->findAll();
        $data = array();
        foreach ($contacts as $idx => $contact) {
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
        foreach ($query as $idx => $entry) {
            $data[] = array(
                "name" => $entry["user"]["name"],
                "mail" => $entry["email"],
                "tel" => $entry["phone"],
            );
        }   
        return $data[0];
    }
}