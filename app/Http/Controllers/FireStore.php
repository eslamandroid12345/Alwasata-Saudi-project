<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Driver\Exception;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Transaction;
use function GuzzleHttp\json_decode;

class FireStore extends Controller
{
    protected $db;
    protected $name;

    public function __construct(string $collection)
    {
        $this->db = new FirestoreClient([
            'projectId' => 'flash-chat-55470',
            //            'projectId' => 'alwsata-860b4',
            //            'keyFile' => json_decode(file_get_contents(__DIR__ . '/firebaseKey1.json'), true),
            'keyFile'   => json_decode(file_get_contents(__DIR__.'/firebaseKey.json'), true),
        ]);
        $this->name = $collection;
    }

    public function getDocument(string $name)
    {
        //            if($this->db->collection($this->name)->document($name)->snapshot()->exists())
        //            {
        return $this->db->collection($this->name)->document($name)->snapshot()->data();
        //            }else{
        //               return "Document Not Found";
        //            }
    }

    public function getWhere(string $document, string $document2)
    {
        $arr = [];
        $query = $this->db->collection($this->name)
            ->document($document)
            ->collection('chats')
            ->document($document2)
            ->snapshot()->data();
        return $query;
    }

    public function getMessageChat(string $document, $value)
    {
        $citiesRef = $this->db->collection($this->name)->document($document)->collection('messages');
        $query = $citiesRef->where('senderId', '=', $value);
        $documents = $query->documents();
        foreach ($documents as $document) {
            if ($document->exists()) {
                return $document->data();
            }
            else {
                return false;
            }
        }
    }

    public function getLatestMessage(string $field, string $operator, $value)
    {
        $arr = [];
        $query = $this->db->collection($this->name)
            ->where($field, $operator, $value)
            ->documents()
            ->rows();
        if (!empty($query)) {
            foreach ($query as $item) {
                $arr[] = $item->data();
            }
        }
        return $arr;
    }

    public function getT(string $name)
    {
        $usersRef = $this->db->collectionGroup($name);
        $querySnapshot = $usersRef->documents()->rows();
        echo sprintf('Found %d documents!', $querySnapshot->size());

    }

    //    public function newDocument(string $name,string $name2, string $name3,array $data = [])
    public function newDocument(string $name, string $name2, string $name3, array $data = [])
    {
        try {
            $this->db->collection($this->name)->document($name)
                ->collection($name2)
                ->document($name3)
                ->create($data);

            return true;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function newCollection($documentName, string $documentName2, array $data = [])
    {
        try {
            $this->db->collection($this->name)
                ->document($documentName)
                ->collection('messages')
                ->document($documentName)
                ->set($data);
            return true;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function dropDocument(string $name)
    {
        $this->db->collection($this->name)->document($name)->delete();
    }

    public function dropCollection(string $name)
    {
        $documents = $this->db->collection($name)->limit(1)->documents();
        while (!$documents->isEmpty()) {
            foreach ($documents as $item) {
                $item->reference()->delete();
            }
        }
    }

    public function update(string $id, array $data = [])
    {
        try {
            $this->db->collection($this->name)->document($id)->set($data);
            return true;
        }
        catch (Exception $exception) {
            return $exception->getMessage();
        }

    }

    public function addnew(string $name)
    {
        try {
            $this->db->collection($name)->create();
            return true;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //////////////////////////////////////////////////////////////
    public function newDocument2($customerId, $userId, string $firstDocument, string $secondDocument, array $data = [], array $messageData = [])
    {
        // check if the users - room date have same sender and receiver id`s
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$customerId, $userId]);
        $checkEmpty = $containsQuery->documents()->rows();
        if ($checkEmpty === []) {
            try {
                $query = $this->db->collection($this->name)
                    ->document($firstDocument)
                    ->parent()
                    ->add($data);
                if ($query) {
                    $addSub = $query->collection('chat_messages')
                        ->document($secondDocument)
                        ->set($messageData);
                }
                return true;
            }
            catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        else {
            //            return "exists";
            $checkDoc = $containsQuery->documents();
            foreach ($checkDoc as $document) {
                if ($document->exists()) {
                    printf('Document data for document %s:'.PHP_EOL, $document->id());
                    $documentID = (int) date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(1000, 999));
                    $docRef = $this->db->collection('messages')->document($document->id())
                        ->collection('chat_messages')
                        ->document($documentID)
                        ->parent()
                        ->add($messageData);
                    return true;

                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
    }

    public function getMessagesWhereUserAndCustomer($customerId, $userId)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$customerId, $userId]);
        $checkDoc = $containsQuery->documents();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {
                    $arr = [];
                    $docRef = $this->db->collection('messages')->document($document->id())
                        ->collection('chat_messages')
                        ->orderBy('created_at', 'ASC');
                    $get = $docRef->documents();
                    if (!empty($get)) {
                        foreach ($get as $item) {
                            $arr[] = $item->data();
                        }
                    }
                    return $arr;
                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }
    }

    public function getMessagesWhereUserAndCustomerSalesInterface($userId, $customerId)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$userId, $customerId]);
        $checkDoc = $containsQuery->documents();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {

                    $arr = [];
                    $docRef = $this->db->collection('messages')->document($document->id())
                        ->collection('chat_messages')
                        ->orderBy('created_at', 'DESC');
                    $get = $docRef->documents();
                    if (!empty($get)) {
                        foreach ($get as $item) {
                            $arr[] = $item->data();
                        }
                    }
                    return $arr;
                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }
    }

    public function getLastMessageFromClient($user_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', 'array-contains-any', [$user_id]);
        $checkDoc = $containsQuery->documents();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {

                    $arr = [];
                    $docRef = $this->db->collection('messages')->document($document->id())
                        ->collection('chat_messages')
                        ->where('is_read', '==', 0)
                        ->where('from_type', '==', 'App\customer');
                    $get = $docRef->documents();
                    if (!empty($get)) {
                        foreach ($get as $item) {
                            $arr[] = $item->data();
                        }
                    }
                    return $arr;

                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }

    }

    public function getAllUnreadMessageFromClient($user_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', 'array-contains-any', [$user_id]);
        $checkDoc = $containsQuery->documents()->rows();
        for ($x = 0; $x <= (count($checkDoc)); $x++) {
            foreach ($checkDoc as $document) {
                $arr = [];
                $docRef = $this->db->collection('messages')->document($document->id())
                    ->collection('chat_messages')
                    ->where('is_read', '==', 0)
                    ->where('from_type', '==', 'App\customer');
                $get = $docRef->documents();
                if (!empty($get)) {
                    foreach ($get as $item) {
                        $arr[] = $item->data();
                    }
                }
                return $arr;
            }
        }
    }

    public function countAllUnreadMessageFromClient($user_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', 'array-contains-any', [$user_id]);
        $checkDoc = $containsQuery->documents()->rows();
        for ($x = 0; $x <= (count($checkDoc)); $x++) {
            foreach ($checkDoc as $document) {
                $docRef = $this->db->collection('messages')->document($document->id())
                    ->collection('chat_messages')
                    ->where('is_read', '==', 0)
                    ->where('from_type', '==', 'App\customer');
                $get = $docRef->documents();
                if (!empty($get)) {
                    foreach ($get as $item) {
                        $arr[] = $item->data();
                    }
                }
            }
            if (empty($arr)) {
                return 0;
            }
            else {
                return count($arr);
            }
        }
    }

    public function getAllMessagesFromClients($customer_id, $user_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$customer_id, $user_id]);
        $checkDoc = $containsQuery->documents();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {

                    $arr = [];
                    $docRef = $this->db->collection('messages')->document($document->id())
                        ->collection('chat_messages')
                        ->where('is_read', '==', 0)
                        ->orderBy('created_at', 'DESC')
                        ->limit(1);
                    $get = $docRef->documents();
                    if (!empty($get)) {
                        foreach ($get as $item) {
                            $arr[] = $item->data();
                        }
                    }
                    return $arr;
                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }
    }

    public function getAllUsersWhereSaleAgent($user_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', 'array-contains-any', [$user_id]);
        $checkDoc = $containsQuery->documents();
        if ($checkDoc) {
            $arr = [];
            foreach ($checkDoc as $item) {
                $arr[] = $item->data();
            }
            return $arr;
        }
    }

    public function markAllMessageAsRead($customer_id, $user_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$customer_id, $user_id]);
        $checkDoc = $containsQuery->documents()->rows();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {
                    $docRef = $this->db->collection('messages')
                        ->document($document->id())
                        ->collection('chat_messages')
                        ->where('receiverId', '==', $user_id)
                        ->documents();
                    if ($docRef) {
                        foreach ($docRef as $item) {
                            //                            printf('Document data for document %s:' . PHP_EOL, $item->id());
                            $doc = $this->db->collection('messages')
                                ->document($document->id())
                                ->collection('chat_messages')
                                ->document($item->id());
                            $tr = $this->db->runTransaction(function (Transaction $transaction) use ($doc) {
                                $transaction->update($doc, [
                                    ['path' => 'is_read', 'value' => 1],
                                ]);
                            });
                        }
                        return true;
                    }
                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }
    }

    public function markAllMessageAsReadCustomer($customer_id, $user_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$customer_id, $user_id]);
        $checkDoc = $containsQuery->documents()->rows();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {
                    $docRef = $this->db->collection('messages')
                        ->document($document->id())
                        ->collection('chat_messages')
                        ->where('receiverId', '==', $customer_id)
                        ->documents();
                    if ($docRef) {
                        foreach ($docRef as $item) {
                            //                            printf('Document data for document %s:' . PHP_EOL, $item->id());
                            $doc = $this->db->collection('messages')
                                ->document($document->id())
                                ->collection('chat_messages')
                                ->document($item->id());
                            $tr = $this->db->runTransaction(function (Transaction $transaction) use ($doc) {
                                $transaction->update($doc, [
                                    ['path' => 'is_read', 'value' => 1],
                                ]);
                            });
                        }
                        return true;
                    }
                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }
    }

    public function getAllMessageFromSelectedCustomer($user_id, $my_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$user_id, $my_id]);
        $checkDoc = $containsQuery->documents();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {

                    $arr = [];
                    $docRef = $this->db->collection('messages')->document($document->id())
                        ->collection('chat_messages')
                        ->orderBy('created_at', 'ASC');
                    $get = $docRef->documents();
                    if (!empty($get)) {
                        foreach ($get as $item) {
                            $arr[] = $item->data();
                        }
                    }
                    return $arr;
                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }
    }

    public function countUnreadMessagesCustomer($customer_id, $agent_id)
    {
        $check = $this->db->collection($this->name);
        $containsQuery = $check->where('room_date.users', '==', [$customer_id, $agent_id]);
        $checkDoc = $containsQuery->documents();
        if ($checkDoc) {
            foreach ($checkDoc as $document) {
                if ($document->exists()) {

                    $arr = [];
                    $docRef = $this->db->collection('messages')->document($document->id())
                        ->collection('chat_messages')
                        ->where('is_read', '==', 0)
                        ->where('from_type', '==', 'App\User');
                    $get = $docRef->documents();
                    if (!empty($get)) {
                        foreach ($get as $item) {
                            $arr[] = $item->data();
                        }
                    }
                    return $arr;
                }
                else {
                    printf('Document does not exist!');
                }
            }
        }
        else {
            return false;
        }
    }
}



