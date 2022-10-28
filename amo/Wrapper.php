<?php


class Wrapper
{
    private $phone;
    private $cleanPhone;
    private  $contact;
    private  $lead;
    private $tags = [];
    private $leadName = 'Заявка с сайта edu';
    private $contactName = 'Новый контакт';
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    private function clearPhone()
    {
        $phone = substr($this->phone, 1);
        $phone = substr($phone, 1);
        $this->cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    }

    public function findContact()
    {
        $this->clearPhone();
        $contacts = \AmoCRM\AmoAPI::getContacts(['query' => $this->cleanPhone]);
        if ($contacts) {
            $this->contact = new \AmoCRM\AmoContact([
                'id' => $contacts[0]['id']
            ]);
        } else {
            $this->contact = new \AmoCRM\AmoContact();
            $this->contact->name = 'Новый контакт с сайта : https://edu.jusaninvest.kz';
            $this->contact->setCustomFields([
                '114347' => [[
                    'value' => $this->phone,
                    'enum' => 'WORK'
                ]]
            ]);
            $this->contact->id = $this->contact->save();
        }
    }
    private function getSortUsers()
    {
        $users = json_decode(file_get_contents('users.json'),true);
        usort($users, function($a,$b){
            return ($a['count']-$b['count']);
        });
        return $users;
    }
    public function manageOrder()
    {
        $users = $this->getSortUsers();
        $users[0]['count'] = $users[0]['count'] +1;
        file_put_contents('users.json',json_encode($users));
        $this->contact->name = $this->contactName;
        $this->contact->responsible_user_id =  $users[0]['id'];
        $this->lead->responsible_user_id =  $users[0]['id'];
        $this->contact->save();
        $this->lead->save();

    }


    public function createOrder()
    {
        if(!isset($this->contact)){
            throw new Exception('контакт не был создан');
        }
        $lead = new \AmoCRM\AmoLead();
        $lead->addContacts($this->contact->id);
        $lead->status_id = 52279789;
        $lead->name = $this->leadName;
        $lead->addTags($this->tags);
        $this->lead = $lead;
        $this->lead->id = $lead->save();

    }

    public function addContactName($name)
    {
        $this->contactName = $name;
        return $this;
    }

    public function addLeadName(string $name)
    {
        $this->leadName = $name;
        return $this;
    }

    public function addTags(...$tags)
    {
        $this->tags = $tags;
        return $this;
    }
}